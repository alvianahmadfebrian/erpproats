@extends('layouts.app')

@section('title', 'Manajemen Vendor - Proats Music Center')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 w-full pb-12">
    <!-- Alerts & Errors -->
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-green-50 border border-green-200 flex items-start gap-3 mb-4 animate-fade-in shadow-sm font-body-sm">
            <span class="material-symbols-outlined text-green-600 shrink-0">check_circle</span>
            <span class="font-body-sm text-body-sm font-semibold text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-error-container border border-error/20 flex items-start gap-3 mb-4 animate-fade-in shadow-sm font-body-sm">
            <span class="material-symbols-outlined text-error shrink-0">error</span>
            <span class="font-body-sm text-body-sm font-semibold text-on-error-container">{{ $errors->first() }}</span>
        </div>
    @endif

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
            <p class="font-body-sm text-body-sm text-on-surface-variant tracking-wide uppercase">Partners</p>
            <h2 class="font-display-lg text-display-lg text-primary mt-1">Manajemen Vendor</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Kelola mitra penyedia alat, aksesori, dan layanan untuk Proats Music Center.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2 shrink-0">
            <a href="{{ route('vendors.export', request()->query()) }}" class="bg-surface-container-lowest border border-outline-variant px-4 py-2.5 rounded-full font-body-sm font-semibold text-on-surface-variant hover:bg-surface-container-high transition-colors flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">download</span> Export Data
            </a>
            <button onclick="openAddModal()" class="bg-indigo-600 text-white px-5 py-2.5 rounded-full font-body-sm font-semibold hover:bg-indigo-700 transition-colors flex items-center gap-2 shadow-md hover:shadow-lg">
                <span class="material-symbols-outlined text-[18px]">add</span> Tambah Vendor
            </button>
        </div>
    </div>

    <!-- Stats Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card: Total Vendor -->
        <div class="group relative bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 hover:shadow-lg hover:border-secondary/40 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-secondary/5 to-transparent rounded-bl-full"></div>
            <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center mb-3 group-hover:bg-secondary/20 transition-colors">
                <span class="material-symbols-outlined text-secondary text-[22px]">storefront</span>
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">Total Vendor</p>
            <p class="font-display-lg text-display-lg text-primary leading-none font-bold">{{ $totalVendorsCount }}</p>
        </div>

        <!-- Card: Vendor Aktif -->
        <div class="group relative bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 hover:shadow-lg hover:border-emerald-500/40 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-emerald-500/5 to-transparent rounded-bl-full"></div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-3 group-hover:bg-emerald-500/20 transition-colors">
                <span class="material-symbols-outlined text-emerald-600 text-[22px]">verified</span>
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">Vendor Aktif</p>
            <p class="font-display-lg text-display-lg text-primary leading-none font-bold">{{ $activeVendorsCount }}</p>
        </div>

        <!-- Card: Total Nilai Pengadaan -->
        <div class="group relative bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 hover:shadow-lg hover:border-indigo-500/40 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-indigo-500/5 to-transparent rounded-bl-full"></div>
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center mb-3 group-hover:bg-indigo-500/20 transition-colors">
                <span class="material-symbols-outlined text-indigo-600 text-[22px]">payments</span>
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">Total Nilai Pengadaan (YTD)</p>
            <h3 class="font-data-mono text-data-mono text-[22px] font-bold text-primary">Rp {{ number_format($nilaiPengadaan, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Data Table Section -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden flex flex-col shadow-sm">
        <!-- Toolbar -->
        <div class="p-4 border-b border-outline-variant flex flex-col sm:flex-row justify-between items-start sm:items-center bg-surface-container-low/40 gap-4">
            <form id="filter-form" action="{{ route('vendors') }}" method="GET" class="flex flex-wrap gap-2">
                @if(request()->filled('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}"/>
                @endif
                <div class="relative">
                    <label class="sr-only" for="filter-kategori">Filter Kategori</label>
                    <select name="category" onchange="this.form.submit()" class="border border-outline-variant rounded-full bg-surface-container-lowest font-body-sm text-on-surface-variant px-4 py-2 pr-8 appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500/50" id="filter-kategori">
                        <option value="">Semua Kategori</option>
                        <option value="Alat Musik" {{ request('category') == 'Alat Musik' ? 'selected' : '' }}>Alat Musik</option>
                        <option value="Aksesori" {{ request('category') == 'Aksesori' ? 'selected' : '' }}>Aksesori</option>
                        <option value="Service" {{ request('category') == 'Service' ? 'selected' : '' }}>Service</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant" style="font-size: 18px;">expand_more</span>
                </div>
                <div class="relative">
                    <label class="sr-only" for="filter-status">Filter Status</label>
                    <select name="status" onchange="this.form.submit()" class="border border-outline-variant rounded-full bg-surface-container-lowest font-body-sm text-on-surface-variant px-4 py-2 pr-8 appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500/50" id="filter-status">
                        <option value="">Semua Status</option>
                        <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Suspen" {{ request('status') == 'Suspen' ? 'selected' : '' }}>Suspen</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant" style="font-size: 18px;">expand_more</span>
                </div>
            </form>
            <div class="font-body-sm text-body-sm text-on-surface-variant font-medium">
                Menampilkan {{ $vendors->firstItem() ?? 0 }}-{{ $vendors->lastItem() ?? 0 }} dari {{ $vendors->total() }} Vendor
            </div>
        </div>
        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-surface-container-low/40 border-b border-outline-variant">
                        <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold">Nama Vendor</th>
                        <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold">Kategori</th>
                        <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold">Kontak</th>
                        <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold">Alamat</th>
                        <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold whitespace-nowrap">Nilai Pengadaan</th>
                        <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold">Status</th>
                        <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/60">
                    @foreach($vendors as $v)
                    <tr class="group/row hover:bg-surface-container-low/30 transition-all duration-200">
                        <td class="py-4 px-5 font-body-md font-semibold text-primary">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500/5 to-indigo-500/15 flex items-center justify-center text-indigo-600 font-semibold shadow-sm transition-all duration-300 group-hover/row:scale-105">
                                    <span class="material-symbols-outlined text-[20px]">store</span>
                                </div>
                                <span class="truncate hover:text-indigo-600 transition-colors">{{ $v->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-5 text-on-surface-variant font-body-sm">{{ $v->category }}</td>
                        <td class="py-4 px-5 text-on-surface-variant font-body-sm">{{ $v->contact }}</td>
                        <td class="py-4 px-5 text-on-surface-variant font-body-sm truncate max-w-[200px]" title="{{ $v->address }}">{{ $v->address }}</td>
                        <td class="py-4 px-5 text-primary font-bold whitespace-nowrap font-data-mono">Rp {{ number_format($v->procurement_cost, 0, ',', '.') }}</td>
                        <td class="py-4 px-5">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-label-caps text-[10px] font-bold {{ $v->status == 'Aktif' ? 'bg-green-500/10 text-green-700' : 'bg-rose-500/10 text-rose-700' }}">{{ $v->status }}</span>
                        </td>
                        <td class="py-4 px-5 text-right">
                            <div class="flex justify-end gap-2 items-center">
                                <button onclick="openEditModal({{ json_encode($v) }})" class="p-2 rounded-xl border border-outline-variant hover:border-indigo-600 hover:bg-indigo-50/50 text-on-surface-variant hover:text-indigo-600 transition-all duration-200 shadow-sm" title="Edit Vendor">
                                    <span class="material-symbols-outlined text-[18px] block">edit</span>
                                </button>
                                <form action="{{ route('vendors.destroy', $v->id) }}" method="POST" class="inline-block" onsubmit="event.preventDefault(); showCustomConfirm(this, 'Apakah Anda yakin ingin menghapus vendor ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-xl border border-outline-variant hover:border-error hover:bg-error/5 text-on-surface-variant hover:text-error transition-all duration-200 shadow-sm" title="Delete Vendor">
                                        <span class="material-symbols-outlined text-[18px] block">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if($vendors->isEmpty())
                    <tr>
                        <td colspan="7" class="py-12 text-center text-on-surface-variant font-body-md">
                            <div class="flex flex-col items-center justify-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-[40px] text-outline-variant mb-2">supervisor_account</span>
                                <p>Tidak ada vendor ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="p-4 border-t border-outline-variant bg-surface-container-low/40 flex justify-between items-center text-on-surface-variant font-body-sm">
            <span>Menampilkan {{ $vendors->firstItem() ?? 0 }} - {{ $vendors->lastItem() ?? 0 }} dari {{ $vendors->total() }} vendor</span>
            <div class="flex gap-2">
                @if($vendors->onFirstPage())
                    <button class="px-4 py-2 border border-outline-variant rounded-full opacity-50 cursor-not-allowed text-xs font-semibold" disabled>Sebelumnya</button>
                @else
                    <a href="{{ $vendors->appends(request()->query())->previousPageUrl() }}" class="px-4 py-2 border border-outline-variant rounded-full text-xs font-semibold hover:bg-surface-container-low transition-colors">Sebelumnya</a>
                @endif

                @if($vendors->hasMorePages())
                    <a href="{{ $vendors->appends(request()->query())->nextPageUrl() }}" class="px-4 py-2 border border-outline-variant rounded-full text-xs font-semibold hover:bg-surface-container-low transition-colors">Selanjutnya</a>
                @else
                    <button class="px-4 py-2 border border-outline-variant rounded-full opacity-50 cursor-not-allowed text-xs font-semibold" disabled>Selanjutnya</button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Vendor Modal -->
<div id="add-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-md shadow-2xl animate-scale-up">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600">store</span>
                Tambah Vendor
            </h3>
            <button onclick="closeAddModal()" class="p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined block">close</span>
            </button>
        </div>
        <form action="{{ route('vendors.store') }}" method="POST" class="space-y-4 font-body-sm">
            @csrf
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Nama Vendor</label>
                <input type="text" name="name" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all" placeholder="Contoh: Yamaha Music Indonesia"/>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Kategori</label>
                <select name="category" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer transition-all">
                    <option value="Alat Musik">Alat Musik</option>
                    <option value="Aksesori">Aksesori</option>
                    <option value="Service">Service</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Email Kontak</label>
                <input type="email" name="contact" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all" placeholder="Contoh: sales@yamaha.co.id"/>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Alamat</label>
                <input type="text" name="address" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all" placeholder="Contoh: Jl. Gatot Subroto Kav. 4, Jakarta"/>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5">Nilai Pengadaan (Rp)</label>
                    <input type="number" name="procurement_cost" min="0" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all" placeholder="Contoh: 150000000"/>
                </div>
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5">Status</label>
                    <select name="status" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer transition-all">
                        <option value="Aktif">Aktif</option>
                        <option value="Suspen">Suspen</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2.5 pt-4 border-t border-outline-variant">
                <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors shadow-md">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Vendor Modal -->
<div id="edit-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-md shadow-2xl animate-scale-up">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600">edit_note</span>
                Edit Vendor
            </h3>
            <button onclick="closeEditModal()" class="p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined block">close</span>
            </button>
        </div>
        <form id="edit-form" action="" method="POST" class="space-y-4 font-body-sm">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Nama Vendor</label>
                <input type="text" id="edit-name" name="name" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Kategori</label>
                <select id="edit-category" name="category" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer transition-all">
                    <option value="Alat Musik">Alat Musik</option>
                    <option value="Aksesori">Aksesori</option>
                    <option value="Service">Service</option>
                </select>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Email Kontak</label>
                <input type="email" id="edit-contact" name="contact" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Alamat</label>
                <input type="text" id="edit-address" name="address" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5">Nilai Pengadaan (Rp)</label>
                    <input type="number" id="edit-procurement-cost" name="procurement_cost" min="0" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
                </div>
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5">Status</label>
                    <select id="edit-status" name="status" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer transition-all">
                        <option value="Aktif">Aktif</option>
                        <option value="Suspen">Suspen</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-2.5 pt-4 border-t border-outline-variant">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors shadow-md">Simpan Perubahan</button>
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
        <h3 class="font-headline-sm text-headline-sm text-primary mb-2">Konfirmasi Hapus</h3>
        <p id="confirm-message" class="text-on-surface-variant mb-6 text-sm">Apakah Anda yakin ingin menghapus data ini?</p>
        <div class="flex justify-center gap-3">
            <button onclick="closeConfirmModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
            <button id="confirm-submit-btn" class="px-6 py-2.5 bg-error text-white rounded-full font-semibold hover:bg-red-700 transition-colors shadow-md">Hapus</button>
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
