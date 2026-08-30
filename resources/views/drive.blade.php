@extends('layouts.app')

@section('title', 'Google Drive - Proats Music Center')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 w-full pb-12">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 shrink-0">
        <div>
            <p class="font-body-sm text-body-sm text-on-surface-variant tracking-wide uppercase">Cloud Storage</p>
            <h2 class="font-display-lg text-display-lg text-primary mt-1 flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600 text-[32px]">cloud</span>
                Google Drive Integration
            </h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Kelola berkas, dokumen kontrak, materi rekaman secara langsung dari sistem ERP.</p>
        </div>
        @if($step == 'explorer')
        <div class="flex gap-2">
            <button onclick="openFolderModal()" class="bg-surface-container-lowest border border-outline-variant hover:bg-surface-container-high px-4 py-2 rounded-full font-body-sm font-semibold text-on-surface-variant flex items-center gap-2 shadow-sm transition-all">
                <span class="material-symbols-outlined text-[18px]">create_new_folder</span> Baru
            </button>
            <button onclick="openUploadModal()" class="bg-indigo-600 text-white px-5 py-2 rounded-full font-body-sm font-semibold hover:bg-indigo-700 transition-colors shadow-md flex items-center gap-2 hover:shadow-lg">
                <span class="material-symbols-outlined text-[18px]">upload_file</span> Upload Berkas
            </button>
            <a href="{{ route('drive.disconnect') }}" class="p-2 bg-rose-50 border border-rose-200 text-error hover:bg-rose-100 rounded-full flex items-center justify-center shadow-sm transition-colors" title="Putuskan Google Drive">
                <span class="material-symbols-outlined text-[20px] block">link_off</span>
            </a>
        </div>
        @endif
    </div>

    <!-- Alerts & Errors -->
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-green-50 border border-green-200 flex items-start gap-3 animate-fade-in shadow-sm font-body-sm">
            <span class="material-symbols-outlined text-green-600 shrink-0">check_circle</span>
            <span class="font-body-sm text-body-sm font-semibold text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-error-container border border-error/20 flex items-start gap-3 animate-fade-in shadow-sm font-body-sm">
            <span class="material-symbols-outlined text-error shrink-0">error</span>
            <span class="font-body-sm text-body-sm font-semibold text-on-error-container">{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- STEP 1: CONFIGURE CREDENTIALS -->
    @if($step == 'configure')
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 shadow-sm max-w-2xl mx-auto space-y-6">
        <div class="text-center space-y-2">
            <div class="w-14 h-14 bg-indigo-500/10 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-2">
                <span class="material-symbols-outlined text-[36px]">api</span>
            </div>
            <h3 class="font-headline-sm text-headline-sm text-primary">Konfigurasi Google API</h3>
            <p class="font-body-sm text-on-surface-variant max-w-md mx-auto">
                Silakan masukkan Client ID dan Client Secret Google Cloud Project Anda untuk mengaktifkan koneksi Google Drive langsung dengan fitur CRUD.
            </p>
        </div>

        <form action="{{ route('drive.credentials') }}" method="POST" class="space-y-4 font-body-sm">
            @csrf
            <div>
                <label class="block font-semibold text-on-surface mb-1.5" for="client-id-input">Google Client ID</label>
                <input type="text" id="client-id-input" name="client_id" value="{{ $clientId }}" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all" placeholder="Contoh: 123456-abc.apps.googleusercontent.com"/>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5" for="client-secret-input">Google Client Secret</label>
                <input type="password" id="client-secret-input" name="client_secret" value="{{ $clientSecret }}" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all" placeholder="Kunci Client Secret dari GCP"/>
            </div>
            <div class="p-4 bg-surface-container-low rounded-xl border border-outline-variant/60 space-y-2">
                <p class="font-semibold text-xs text-primary flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px] text-indigo-600">info</span> 
                    Panduan Mengisi Otorisasi Redirect URI:
                </p>
                <p class="text-[11.5px] text-on-surface-variant leading-relaxed">
                    Di Google Cloud Console, tambahkan URL redirect ini ke bagian <strong>Authorized Redirect URIs</strong> proyek Anda:
                    <code class="block mt-1 p-2 bg-surface-container-lowest border border-outline-variant/50 rounded font-data-mono text-[10px] text-indigo-600 select-all">{{ route('drive.callback') }}</code>
                </p>
            </div>
            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-semibold hover:bg-indigo-700 transition-colors shadow-md">Simpan Kredensial</button>
        </form>
    </div>

    <!-- STEP 2: CONNECT GOOGLE ACCOUNT -->
    @elseif($step == 'connect')
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-8 shadow-sm max-w-md mx-auto text-center space-y-6">
        <div class="w-16 h-16 bg-indigo-500/10 text-indigo-600 rounded-2xl flex items-center justify-center mx-auto">
            <span class="material-symbols-outlined text-[40px]">link</span>
        </div>
        <div class="space-y-2">
            <h3 class="font-headline-sm text-headline-sm text-primary">Hubungkan Akun Google</h3>
            <p class="font-body-sm text-on-surface-variant leading-relaxed">
                ERP memerlukan izin akses dari Anda untuk mengelola file (list, upload, buat folder, hapus) di dalam Google Drive Anda secara aman.
            </p>
        </div>
        <a href="{{ $authUrl }}" class="inline-flex justify-center items-center gap-2.5 w-full bg-indigo-600 text-white py-3 px-6 rounded-full font-semibold hover:bg-indigo-700 transition-colors shadow-md hover:shadow-lg">
            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                <path d="M12.24 10.285V14.4h6.887c-.648 2.41-2.519 4.114-6.887 4.114-4.68 0-8.473-3.83-8.473-8.514s3.793-8.514 8.473-8.514c2.097 0 4.025.76 5.534 2.115l3.203-3.2A13.486 13.486 0 0012.24 0C5.58 0 0 5.373 0 12s5.58 12 12.24 12c6.96 0 11.57-4.89 11.57-11.79 0-.795-.085-1.4-.24-1.925H12.24z"/>
            </svg>
            Hubungkan Akun Google Anda
        </a>
    </div>

    <!-- STEP 3: FILE EXPLORER CRUD -->
    @elseif($step == 'explorer')
    <!-- File Manager Box -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl flex flex-col shadow-sm">
        
        <!-- Explorer Header: Navigation Breadcrumbs -->
        <div class="p-4 border-b border-outline-variant flex items-center bg-surface-container-low/40 rounded-t-2xl font-body-sm text-on-surface-variant overflow-x-auto whitespace-nowrap gap-1.5">
            <a href="{{ route('drive') }}" class="hover:text-indigo-600 transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-[18px]">cloud</span>
                My Drive
            </a>
            
            @foreach($breadcrumbs as $bc)
            <span class="material-symbols-outlined text-[16px] text-outline-variant">chevron_right</span>
            <a href="{{ route('drive', ['folder' => $bc['id']]) }}" class="hover:text-indigo-600 transition-colors last:font-bold last:text-primary">
                {{ $bc['name'] }}
            </a>
            @endforeach
        </div>

        <!-- Files List Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-surface-container-low/20 border-b border-outline-variant/60 font-label-caps text-label-caps text-on-surface-variant">
                        <th class="py-3 px-5 font-bold">Nama</th>
                        <th class="py-3 px-5 font-bold">Tanggal Dibuat</th>
                        <th class="py-3 px-5 font-bold">Ukuran</th>
                        <th class="py-3 px-5 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/60 font-body-sm">
                    @foreach($files as $file)
                    @php
                        $isFolder = ($file->mimeType == 'application/vnd.google-apps.folder');
                    @endphp
                    <tr class="group/row hover:bg-surface-container-low/30 transition-all duration-200">
                        <td class="py-3.5 px-5">
                            @if($isFolder)
                            <a href="{{ route('drive', ['folder' => $file->id]) }}" class="flex items-center gap-3 font-semibold text-primary hover:text-indigo-600 transition-colors">
                                <span class="material-symbols-outlined text-amber-500 text-[24px]">folder</span>
                                <span class="truncate">{{ $file->name }}</span>
                            </a>
                            @else
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-indigo-500 text-[24px]">
                                    @if(str_contains($file->mimeType, 'pdf')) picture_as_pdf
                                    @elseif(str_contains($file->mimeType, 'image')) image
                                    @elseif(str_contains($file->mimeType, 'audio')) audio_file
                                    @elseif(str_contains($file->mimeType, 'word') || str_contains($file->mimeType, 'document')) description
                                    @elseif(str_contains($file->mimeType, 'sheet')) table_view
                                    @else insert_drive_file
                                    @endif
                                </span>
                                <span class="text-on-surface truncate">{{ $file->name }}</span>
                            </div>
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-on-surface-variant">
                            {{ date('d M Y, H:i', strtotime($file->createdTime)) }}
                        </td>
                        <td class="py-3.5 px-5 text-on-surface-variant font-data-mono text-xs">
                            @if($isFolder)
                                -
                            @else
                                {{ $file->size ? round($file->size / 1024 / 1024, 2) . ' MB' : '0 MB' }}
                            @endif
                        </td>
                        <td class="py-3.5 px-5 text-right">
                            <div class="flex justify-end gap-2 items-center">
                                @if(!$isFolder && $file->webViewLink)
                                <a href="{{ $file->webViewLink }}" target="_blank" class="p-2 rounded-xl border border-outline-variant hover:border-indigo-600 hover:bg-indigo-50/50 text-on-surface-variant hover:text-indigo-600 transition-all duration-200 shadow-sm" title="Buka File">
                                    <span class="material-symbols-outlined text-[18px] block">open_in_new</span>
                                </a>
                                @endif
                                <form action="{{ route('drive.destroy', $file->id) }}?parent={{ $currentFolderId }}" method="POST" class="inline-block" onsubmit="event.preventDefault(); showCustomConfirm(this, 'Apakah Anda yakin ingin menghapus file/folder ini dari Google Drive?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl border border-outline-variant hover:border-error hover:bg-error/5 text-on-surface-variant hover:text-error transition-all duration-200 shadow-sm" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px] block">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if(empty($files))
                    <tr>
                        <td colspan="4" class="py-12 text-center text-on-surface-variant font-body-md">
                            <div class="flex flex-col items-center justify-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-[40px] text-outline-variant mb-2">folder_open</span>
                                <p>Folder ini kosong.</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Folder Modal -->
    <div id="folder-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-sm shadow-2xl animate-scale-up">
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
                <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-indigo-600">create_new_folder</span>
                    Buat Folder Baru
                </h3>
                <button onclick="closeFolderModal()" class="p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined block">close</span>
                </button>
            </div>
            <form action="{{ route('drive.folder') }}" method="POST" class="space-y-4 font-body-sm">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $currentFolderId }}"/>
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5" for="folder-name-input">Nama Folder</label>
                    <input type="text" id="folder-name-input" name="name" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all" placeholder="Contoh: Rekaman Latihan 2026"/>
                </div>
                <div class="flex justify-end gap-2.5 pt-4 border-t border-outline-variant">
                    <button type="button" onclick="closeFolderModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors shadow-md">Buat Folder</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Upload File Modal -->
    <div id="upload-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-sm shadow-2xl animate-scale-up">
            <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
                <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                    <span class="material-symbols-outlined text-indigo-600">upload_file</span>
                    Upload Berkas ke Drive
                </h3>
                <button onclick="closeUploadModal()" class="p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined block">close</span>
                </button>
            </div>
            <form action="{{ route('drive.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4 font-body-sm">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $currentFolderId }}"/>
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5" for="file-upload-input">Pilih File (Maks 10MB)</label>
                    <input type="file" id="file-upload-input" name="file" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all cursor-pointer"/>
                </div>
                <div class="flex justify-end gap-2.5 pt-4 border-t border-outline-variant">
                    <button type="button" onclick="closeUploadModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors shadow-md">Upload</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom Confirm Modal -->
    <div id="confirm-modal" class="fixed inset-0 z-[100] hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-sm shadow-2xl text-center font-body-sm animate-scale-up">
            <div class="w-14 h-14 bg-error/10 text-error rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-[32px]">delete_forever</span>
            </div>
            <h3 class="font-headline-sm text-headline-sm text-primary mb-2">Konfirmasi</h3>
            <p id="confirm-message" class="text-on-surface-variant mb-6 text-sm">Apakah Anda yakin?</p>
            <div class="flex justify-center gap-3">
                <button onclick="closeConfirmModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
                <button id="confirm-submit-btn" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors shadow-md">Hapus</button>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    function openFolderModal() {
        const modal = document.getElementById('folder-modal');
        if (modal) modal.classList.remove('hidden');
    }
    function closeFolderModal() {
        const modal = document.getElementById('folder-modal');
        if (modal) modal.classList.add('hidden');
    }

    function openUploadModal() {
        const modal = document.getElementById('upload-modal');
        if (modal) modal.classList.remove('hidden');
    }
    function closeUploadModal() {
        const modal = document.getElementById('upload-modal');
        if (modal) modal.classList.add('hidden');
    }

    // Custom confirm modal
    let confirmFormRef = null;
    function showCustomConfirm(form, message) {
        confirmFormRef = form;
        document.getElementById('confirm-message').textContent = message;
        document.getElementById('confirm-modal').classList.remove('hidden');
    }
    function closeConfirmModal() {
        confirmFormRef = null;
        document.getElementById('confirm-modal').classList.add('hidden');
    }
    document.getElementById('confirm-submit-btn')?.addEventListener('click', () => {
        if (confirmFormRef) {
            confirmFormRef.submit();
        }
        closeConfirmModal();
    });
</script>
@endpush
