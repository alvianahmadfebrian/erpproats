@extends('layouts.app')

@section('title', 'Manajemen Vendor - Proats Music Center')

@section('content')
<div class="max-w-7xl mx-auto space-y-stack-lg w-full">
    <!-- Alerts & Errors -->
    @if (session('success'))
        <div class="p-stack-sm rounded bg-green-50 border border-green-200 flex items-start gap-2 mb-stack-md font-body-sm">
            <span class="material-symbols-outlined text-green-600 shrink-0">check_circle</span>
            <span class="font-body-sm text-body-sm font-semibold text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-stack-sm rounded bg-error-container border border-error/20 flex items-start gap-2 mb-stack-md font-body-sm">
            <span class="material-symbols-outlined text-error shrink-0">error</span>
            <span class="font-body-sm text-body-sm font-semibold text-on-error-container">{{ $errors->first() }}</span>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
            <h2 class="font-display-lg text-display-lg text-primary">Manajemen Vendor</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Kelola mitra penyedia alat, aksesori, dan layanan untuk Proats Music Center.</p>
        </div>
        <div class="flex gap-stack-sm shrink-0">
            <a href="{{ route('vendors.export', request()->query()) }}" class="btn-secondary px-stack-md py-stack-sm font-body-sm font-bold flex items-center gap-unit hover:bg-surface-container-low transition-colors">
                <span class="material-symbols-outlined" style="font-size: 16px;">download</span> Export Data
            </a>
            <button onclick="openAddModal()" class="btn-primary px-stack-md py-stack-sm font-body-sm font-bold flex items-center gap-unit hover:opacity-90 transition-opacity">
                <span class="material-symbols-outlined" style="font-size: 16px;">add</span> Tambah Vendor
            </button>
        </div>
    </div>
    <!-- Stats Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
        <div class="card-level-1 p-gutter rounded-lg flex flex-col justify-center shadow-sm">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-2">Total Vendor</p>
            <div class="flex items-baseline gap-unit">
                <span class="font-display-lg text-display-lg font-bold text-primary">{{ $totalVendorsCount }}</span>
            </div>
        </div>
        <div class="card-level-1 p-gutter rounded-lg flex flex-col justify-center shadow-sm">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-2">Vendor Vendor Aktif</p>
            <div class="flex items-baseline gap-unit">
                <span class="font-display-lg text-display-lg font-bold text-primary">{{ $activeVendorsCount }}</span>
            </div>
        </div>
        <div class="card-level-1 p-gutter rounded-lg flex flex-col justify-center shadow-sm">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-2">Total Nilai Pengadaan (YTD)</p>
            <div class="flex items-baseline gap-unit">
                <span class="font-data-mono text-data-mono text-[20px] font-bold text-primary">Rp {{ number_format($nilaiPengadaan, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
    <!-- Data Table Section -->
    <div class="card-level-1 rounded-lg overflow-hidden flex flex-col shadow-sm">
        <!-- Toolbar -->
        <div class="p-gutter border-b border-outline-variant flex flex-col sm:flex-row justify-between items-start sm:items-center bg-surface gap-4">
            <form id="filter-form" action="{{ route('vendors') }}" method="GET" class="flex flex-wrap gap-stack-sm">
                @if(request()->filled('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}"/>
                @endif
                <div class="relative">
                    <label class="sr-only" for="filter-kategori">Filter Kategori</label>
                    <select name="category" onchange="this.form.submit()" class="input-field bg-transparent font-body-sm text-body-sm px-stack-sm py-[6px] pr-8 appearance-none cursor-pointer" id="filter-kategori">
                        <option value="">Semua Kategori</option>
                        <option value="Alat Musik" {{ request('category') == 'Alat Musik' ? 'selected' : '' }}>Alat Musik</option>
                        <option value="Aksesori" {{ request('category') == 'Aksesori' ? 'selected' : '' }}>Aksesori</option>
                        <option value="Service" {{ request('category') == 'Service' ? 'selected' : '' }}>Service</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-unit top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant" style="font-size: 16px;">expand_more</span>
                </div>
                <div class="relative">
                    <label class="sr-only" for="filter-status">Filter Status</label>
                    <select name="status" onchange="this.form.submit()" class="input-field bg-transparent font-body-sm text-body-sm px-stack-sm py-[6px] pr-8 appearance-none cursor-pointer" id="filter-status">
                        <option value="">Semua Status</option>
                        <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Suspen" {{ request('status') == 'Suspen' ? 'selected' : '' }}>Suspen</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-unit top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant" style="font-size: 16px;">expand_more</span>
                </div>
            </form>
            <div class="font-body-sm text-body-sm text-on-surface-variant">
                Menampilkan {{ $vendors->firstItem() ?? 0 }}-{{ $vendors->lastItem() ?? 0 }} dari {{ $vendors->total() }} Vendor
            </div>
        </div>
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead class="table-header font-label-caps text-label-caps text-on-surface-variant border-b border-outline-variant font-bold">
                    <tr>
                        <th class="p-4">Nama Vendor</th>
                        <th class="p-4">Kategori</th>
                        <th class="p-4">Kontak</th>
                        <th class="p-4">Alamat</th>
                        <th class="p-4 whitespace-nowrap">Nilai Pengadaan</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="font-body-sm text-body-sm">
                    @foreach($vendors as $v)
                    <tr class="table-row h-[40px] transition-colors">
                        <td class="p-4 font-body-md text-body-md font-semibold text-primary">{{ $v->name }}</td>
                        <td class="p-4 text-on-surface-variant">{{ $v->category }}</td>
                        <td class="p-4 text-on-surface-variant">{{ $v->contact }}</td>
                        <td class="p-4 text-on-surface-variant truncate max-w-[200px]" title="{{ $v->address }}">{{ $v->address }}</td>
                        <td class="p-4 text-on-surface-variant whitespace-nowrap font-data-mono">Rp {{ number_format($v->procurement_cost, 0, ',', '.') }}</td>
                        <td class="p-4">
                            <span class="{{ $v->status == 'Aktif' ? 'badge-active' : 'badge-suspend' }} px-2.5 py-0.5 rounded-xl font-label-caps text-label-caps font-bold">{{ $v->status }}</span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="flex justify-end gap-2 items-center">
                                <button onclick="openEditModal({{ json_encode($v) }})" class="p-1 rounded hover:bg-surface-container-high text-on-surface-variant transition-colors" title="Edit Vendor">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                                <form action="{{ route('vendors.destroy', $v->id) }}" method="POST" class="inline-block" onsubmit="event.preventDefault(); showCustomConfirm(this, 'Apakah Anda yakin ingin menghapus vendor ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded hover:bg-surface-container-high text-error transition-colors" title="Delete Vendor">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if($vendors->isEmpty())
                    <tr>
                        <td colspan="7" class="p-8 text-center text-on-surface-variant">Tidak ada vendor ditemukan.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="p-3 border-t border-outline-variant bg-surface flex justify-between items-center text-on-surface-variant font-body-sm">
            <span>Menampilkan {{ $vendors->firstItem() ?? 0 }} - {{ $vendors->lastItem() ?? 0 }} dari {{ $vendors->total() }} vendor</span>
            <div class="flex gap-2">
                @if($vendors->onFirstPage())
                    <button class="px-3 py-1 border border-outline-variant rounded opacity-50 cursor-not-allowed" disabled>Sebelumnya</button>
                @else
                    <a href="{{ $vendors->appends(request()->query())->previousPageUrl() }}" class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container-low">Sebelumnya</a>
                @endif

                @if($vendors->hasMorePages())
                    <a href="{{ $vendors->appends(request()->query())->nextPageUrl() }}" class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container-low">Selanjutnya</a>
                @else
                    <button class="px-3 py-1 border border-outline-variant rounded opacity-50 cursor-not-allowed" disabled>Selanjutnya</button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Vendor Modal -->
<div id="add-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-md shadow-lg">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary">Tambah Vendor</h3>
            <button onclick="closeAddModal()" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('vendors.store') }}" method="POST" class="space-y-4 font-body-sm">
            @csrf
            <div>
                <label class="block font-bold text-on-surface mb-1">Nama Vendor</label>
                <input type="text" name="name" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary" placeholder="Contoh: Yamaha Music Indonesia"/>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Kategori</label>
                <select name="category" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary cursor-pointer">
                    <option value="Alat Musik">Alat Musik</option>
                    <option value="Aksesori">Aksesori</option>
                    <option value="Service">Service</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Email Kontak</label>
                <input type="email" name="contact" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary" placeholder="Contoh: sales@yamaha.co.id"/>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Alamat</label>
                <input type="text" name="address" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary" placeholder="Contoh: Jl. Gatot Subroto Kav. 4, Jakarta"/>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-on-surface mb-1">Nilai Pengadaan (Rp)</label>
                    <input type="number" name="procurement_cost" min="0" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary" placeholder="Contoh: 150000000"/>
                </div>
                <div>
                    <label class="block font-bold text-on-surface mb-1">Status</label>
                    <select name="status" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary cursor-pointer">
                        <option value="Aktif">Aktif</option>
                        <option value="Suspen">Suspen</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 border border-outline text-primary rounded font-bold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded font-bold hover:bg-secondary transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Vendor Modal -->
<div id="edit-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-md shadow-lg">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary">Edit Vendor</h3>
            <button onclick="closeEditModal()" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="edit-form" action="" method="POST" class="space-y-4 font-body-sm">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-bold text-on-surface mb-1">Nama Vendor</label>
                <input type="text" id="edit-name" name="name" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Kategori</label>
                <select id="edit-category" name="category" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary cursor-pointer">
                    <option value="Alat Musik">Alat Musik</option>
                    <option value="Aksesori">Aksesori</option>
                    <option value="Service">Service</option>
                </select>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Email Kontak</label>
                <input type="email" id="edit-contact" name="contact" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Alamat</label>
                <input type="text" id="edit-address" name="address" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-on-surface mb-1">Nilai Pengadaan (Rp)</label>
                    <input type="number" id="edit-procurement-cost" name="procurement_cost" min="0" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
                </div>
                <div>
                    <label class="block font-bold text-on-surface mb-1">Status</label>
                    <select id="edit-status" name="status" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary cursor-pointer">
                        <option value="Aktif">Aktif</option>
                        <option value="Suspen">Suspen</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-outline text-primary rounded font-bold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded font-bold hover:bg-secondary transition-colors">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Custom Confirm Modal -->
<div id="confirm-modal" class="fixed inset-0 z-[100] hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-sm shadow-lg text-center font-body-sm">
        <h3 class="font-headline-sm text-headline-sm text-primary mb-2">Konfirmasi Hapus</h3>
        <p id="confirm-message" class="text-on-surface-variant mb-6">Apakah Anda yakin ingin menghapus data ini?</p>
        <div class="flex justify-center gap-3">
            <button onclick="closeConfirmModal()" class="px-4 py-2 border border-outline text-primary rounded font-bold hover:bg-surface-container-low transition-colors">Batal</button>
            <button id="confirm-submit-btn" class="px-4 py-2 bg-error text-on-error rounded font-bold hover:bg-red-700 transition-colors">Hapus</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let formToSubmit = null;
    function showCustomConfirm(form, message) {
        formToSubmit = form;
        document.getElementById('confirm-message').textContent = message;
        document.getElementById('confirm-modal').classList.remove('hidden');
    }
    function closeConfirmModal() {
        document.getElementById('confirm-modal').classList.add('hidden');
        formToSubmit = null;
    }
    document.getElementById('confirm-submit-btn').addEventListener('click', function() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
    });

    // Modal state controls
    function openAddModal() {
        document.getElementById('add-modal').classList.remove('hidden');
    }
    function closeAddModal() {
        document.getElementById('add-modal').classList.add('hidden');
    }
    function openEditModal(vendor) {
        document.getElementById('edit-form').action = '/vendors/' + vendor.id;
        document.getElementById('edit-name').value = vendor.name;
        document.getElementById('edit-category').value = vendor.category;
        document.getElementById('edit-contact').value = vendor.contact;
        document.getElementById('edit-address').value = vendor.address;
        document.getElementById('edit-procurement-cost').value = vendor.procurement_cost;
        document.getElementById('edit-status').value = vendor.status;
        document.getElementById('edit-modal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }
</script>
@endpush
