@extends('layouts.app')

@section('title', 'Google Drive - Proats Music Center')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 w-full pb-12 h-[calc(100vh-140px)] flex flex-col">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 shrink-0">
        <div>
            <p class="font-body-sm text-body-sm text-on-surface-variant tracking-wide uppercase">Cloud Storage</p>
            <h2 class="font-display-lg text-display-lg text-primary mt-1 flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600 text-[32px]">cloud</span>
                Google Drive Workspace
            </h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Akses berkas, materi musik, dan dokumen bersama langsung dari sistem ERP.</p>
        </div>
    </div>

    <!-- Configure Folder ID Card -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 shadow-sm shrink-0 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="space-y-1 max-w-xl">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600">settings</span>
                Hubungkan Folder Google Drive
            </h3>
            <p class="font-body-sm text-on-surface-variant leading-relaxed">
                Masukkan Folder ID Google Drive bersama Anda (bisa didapatkan dari URL share folder, contoh: <code class="bg-surface-container-low px-1.5 py-0.5 rounded text-indigo-600 font-data-mono text-[11px]">https://drive.google.com/drive/folders/<strong>1A2B3C4D...</strong></code>). Pastikan akses link folder diatur ke <strong>"Siapa saja dengan link dapat melihat"</strong>.
            </p>
        </div>
        <div class="flex gap-2 w-full md:w-auto shrink-0">
            <label class="sr-only" for="drive-folder-id">Folder ID</label>
            <input type="text" id="drive-folder-id" placeholder="Masukkan Folder ID..." class="w-full md:w-64 border border-outline-variant rounded-full px-4 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-xs font-semibold"/>
            <button onclick="saveFolderId()" class="bg-indigo-600 text-white px-5 py-2 rounded-full font-body-sm font-semibold hover:bg-indigo-700 transition-colors shadow-md shrink-0">Hubungkan</button>
        </div>
    </div>

    <!-- Embedded Google Drive View -->
    <div class="flex-1 min-h-[450px] bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden relative shadow-sm flex flex-col">
        <!-- Spinner Overlay -->
        <div id="drive-loading" class="absolute inset-0 bg-surface-container-lowest/80 z-20 flex flex-col items-center justify-center gap-3 backdrop-blur-sm">
            <div class="w-10 h-10 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
            <p class="font-body-sm text-on-surface-variant font-semibold">Memuat berkas Google Drive...</p>
        </div>

        <!-- Drive Frame -->
        <iframe id="drive-frame" src="" class="w-full h-full flex-1 border-0" allow="autoplay" onload="onFrameLoad()"></iframe>

        <!-- Fallback Message -->
        <div id="drive-fallback" class="hidden absolute inset-0 z-10 flex flex-col items-center justify-center p-8 text-center bg-surface-container-lowest">
            <span class="material-symbols-outlined text-[64px] text-outline-variant mb-3">cloud_off</span>
            <h4 class="font-headline-sm text-headline-sm text-primary mb-2">Folder Google Drive Belum Terhubung</h4>
            <p class="text-on-surface-variant max-w-md text-sm leading-relaxed mb-4">
                Silakan masukkan Folder ID di kolom atas untuk menampilkan dokumen, file audio recital, dan rekaman latihan Proats Music Center di sini secara instan.
            </p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const DEFAULT_ID = '15Z5N16jT41s_X4EsknI7vTfVpGgY0M59'; // Public sample ID fallback

    // Initialize View
    document.addEventListener('DOMContentLoaded', () => {
        let savedId = localStorage.getItem('google_drive_folder_id');
        const inputField = document.getElementById('drive-folder-id');
        const iframe = document.getElementById('drive-frame');
        const fallback = document.getElementById('drive-fallback');
        const loader = document.getElementById('drive-loading');

        if (!savedId) {
            // Show fallback instructions
            iframe.classList.add('hidden');
            loader.classList.add('hidden');
            fallback.classList.remove('hidden');
        } else {
            inputField.value = savedId;
            iframe.src = `https://drive.google.com/embeddedfolderview?id=${savedId}#grid`;
            iframe.classList.remove('hidden');
            fallback.classList.add('hidden');
        }
    });

    function saveFolderId() {
        const inputField = document.getElementById('drive-folder-id');
        const id = inputField.value.trim();

        if (!id) {
            alert('Folder ID tidak boleh kosong.');
            return;
        }

        // Show loading spinner
        const loader = document.getElementById('drive-loading');
        loader.classList.remove('hidden');

        // Persist to localStorage
        localStorage.setItem('google_drive_folder_id', id);

        // Update iframe source
        const iframe = document.getElementById('drive-frame');
        const fallback = document.getElementById('drive-fallback');
        iframe.src = `https://drive.google.com/embeddedfolderview?id=${id}#grid`;
        iframe.classList.remove('hidden');
        fallback.classList.add('hidden');
    }

    function onFrameLoad() {
        const loader = document.getElementById('drive-loading');
        if (loader) {
            loader.classList.add('hidden');
        }
    }
</script>
@endpush
