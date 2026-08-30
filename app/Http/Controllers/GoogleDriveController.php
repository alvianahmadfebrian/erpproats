<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Drive\DriveFile;

class GoogleDriveController extends Controller
{
    private function getGoogleClient()
    {
        $client = new GoogleClient();
        
        $clientId = env('GOOGLE_CLIENT_ID') ?: session('google_client_id');
        $clientSecret = env('GOOGLE_CLIENT_SECRET') ?: session('google_client_secret');
        
        $redirectUri = route('drive.callback');

        if (!$clientId || !$clientSecret) {
            return null;
        }

        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri($redirectUri);
        $client->addScope(GoogleDrive::DRIVE);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        return $client;
    }

    public function index(Request $request)
    {
        $clientId = env('GOOGLE_CLIENT_ID') ?: session('google_client_id');
        $clientSecret = env('GOOGLE_CLIENT_SECRET') ?: session('google_client_secret');

        if (!$clientId || !$clientSecret) {
            return view('drive', [
                'step' => 'configure',
                'clientId' => $clientId,
                'clientSecret' => $clientSecret
            ]);
        }

        $client = $this->getGoogleClient();
        $token = session('google_oauth_token');

        if (!$token) {
            return view('drive', [
                'step' => 'connect',
                'authUrl' => $client->createAuthUrl()
            ]);
        }

        $client->setAccessToken($token);

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                session(['google_oauth_token' => $client->getAccessToken()]);
            } else {
                session()->forget('google_oauth_token');
                return redirect()->route('drive')->withErrors(['error' => 'Sesi Google Drive telah berakhir, silakan hubungkan kembali.']);
            }
        }

        $driveService = new GoogleDrive($client);

        // Get folder navigation path
        $currentFolderId = $request->query('folder', 'root');

        // Fetch files inside the current folder
        try {
            $optParams = [
                'pageSize' => 50,
                'fields' => 'nextPageToken, files(id, name, mimeType, iconLink, webViewLink, webContentLink, size, createdTime)',
                'q' => "'{$currentFolderId}' in parents and trashed = false",
                'orderBy' => 'folder, name'
            ];
            $results = $driveService->files->listFiles($optParams);
            $files = $results->getFiles();
        } catch (\Exception $e) {
            // If token invalid or folder not found, redirect to disconnect
            return redirect()->route('drive.disconnect')->withErrors(['error' => 'Gagal mengambil data dari Google Drive: ' . $e->getMessage()]);
        }

        // Get folder breadcrumbs hierarchy
        $breadcrumbs = [];
        if ($currentFolderId !== 'root') {
            try {
                $folder = $driveService->files->get($currentFolderId, ['fields' => 'id, name, parents']);
                $breadcrumbs[] = [
                    'id' => $folder->id,
                    'name' => $folder->name
                ];
                // Resolve parents if possible (just 1 level for simplicity)
                if (!empty($folder->parents)) {
                    $parentFolder = $driveService->files->get($folder->parents[0], ['fields' => 'id, name']);
                    array_unshift($breadcrumbs, [
                        'id' => $parentFolder->id,
                        'name' => $parentFolder->name
                    ]);
                }
            } catch (\Exception $e) {
                // Ignore
            }
        }

        return view('drive', [
            'step' => 'explorer',
            'files' => $files,
            'currentFolderId' => $currentFolderId,
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function callback(Request $request)
    {
        if ($request->has('error')) {
            return redirect()->route('drive')->withErrors(['error' => 'Akses ditolak: ' . $request->input('error')]);
        }

        if (!$request->has('code')) {
            return redirect()->route('drive')->withErrors(['error' => 'Kode otorisasi tidak ditemukan.']);
        }

        $client = $this->getGoogleClient();
        if (!$client) {
            return redirect()->route('drive')->withErrors(['error' => 'Klien Google belum dikonfigurasi.']);
        }

        try {
            $accessToken = $client->fetchAccessTokenWithAuthCode($request->input('code'));
            session(['google_oauth_token' => $accessToken]);
            return redirect()->route('drive')->with('success', 'Akun Google Drive berhasil dihubungkan!');
        } catch (\Exception $e) {
            return redirect()->route('drive')->withErrors(['error' => 'Gagal menukarkan kode akses: ' . $e->getMessage()]);
        }
    }

    public function disconnect()
    {
        session()->forget('google_oauth_token');
        return redirect()->route('drive')->with('success', 'Akun Google Drive berhasil diputuskan.');
    }

    public function saveCredentials(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
        ]);

        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            
            // Client ID
            if (str_contains($envContent, 'GOOGLE_CLIENT_ID=')) {
                $envContent = preg_replace('/GOOGLE_CLIENT_ID=.*/', 'GOOGLE_CLIENT_ID=' . $validated['client_id'], $envContent);
            } else {
                $envContent .= "\nGOOGLE_CLIENT_ID=" . $validated['client_id'];
            }
            
            // Client Secret
            if (str_contains($envContent, 'GOOGLE_CLIENT_SECRET=')) {
                $envContent = preg_replace('/GOOGLE_CLIENT_SECRET=.*/', 'GOOGLE_CLIENT_SECRET=' . $validated['client_secret'], $envContent);
            } else {
                $envContent .= "\nGOOGLE_CLIENT_SECRET=" . $validated['client_secret'];
            }

            file_put_contents($envPath, $envContent);
        }

        session([
            'google_client_id' => $validated['client_id'],
            'google_client_secret' => $validated['client_secret'],
        ]);

        return redirect()->route('drive')->with('success', 'Kredensial Google API berhasil disimpan!');
    }

    public function createFolder(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'required|string'
        ]);

        $token = session('google_oauth_token');
        $client = $this->getGoogleClient();
        $client->setAccessToken($token);
        $driveService = new GoogleDrive($client);

        try {
            $fileMetadata = new DriveFile([
                'name' => $validated['name'],
                'mimeType' => 'application/vnd.google-apps.folder',
                'parents' => [$validated['parent_id']]
            ]);
            $driveService->files->create($fileMetadata, ['fields' => 'id']);
            return redirect()->route('drive', ['folder' => $validated['parent_id']])->with('success', 'Folder baru berhasil dibuat.');
        } catch (\Exception $e) {
            return redirect()->route('drive', ['folder' => $validated['parent_id']])->withErrors(['error' => 'Gagal membuat folder: ' . $e->getMessage()]);
        }
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', 
            'parent_id' => 'required|string'
        ]);

        $token = session('google_oauth_token');
        $client = $this->getGoogleClient();
        $client->setAccessToken($token);
        $driveService = new GoogleDrive($client);

        try {
            $uploadedFile = $request->file('file');
            $fileMetadata = new DriveFile([
                'name' => $uploadedFile->getClientOriginalName(),
                'parents' => [$request->input('parent_id')]
            ]);

            $content = file_get_contents($uploadedFile->getRealPath());
            $driveService->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $uploadedFile->getMimeType(),
                'uploadType' => 'multipart',
                'fields' => 'id'
            ]);

            return redirect()->route('drive', ['folder' => $request->input('parent_id')])->with('success', 'Berkas berhasil diupload ke Google Drive.');
        } catch (\Exception $e) {
            return redirect()->route('drive', ['folder' => $request->input('parent_id')])->withErrors(['error' => 'Gagal mengupload berkas: ' . $e->getMessage()]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $parentId = $request->query('parent', 'root');
        $token = session('google_oauth_token');
        $client = $this->getGoogleClient();
        $client->setAccessToken($token);
        $driveService = new GoogleDrive($client);

        try {
            $driveService->files->delete($id);
            return redirect()->route('drive', ['folder' => $parentId])->with('success', 'Berkas/folder berhasil dihapus dari Google Drive.');
        } catch (\Exception $e) {
            return redirect()->route('drive', ['folder' => $parentId])->withErrors(['error' => 'Gagal menghapus berkas/folder: ' . $e->getMessage()]);
        }
    }
}
