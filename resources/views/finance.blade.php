@extends('layouts.app')

@section('title', 'Keuangan - Proats Music Center')

@section('content')
<div class="max-w-7xl mx-auto space-y-stack-lg w-full">
    <!-- Alerts & Errors -->
    @if (session('success'))
        <div class="p-stack-sm rounded bg-green-50 border border-green-200 flex items-start gap-2 mb-stack-md">
            <span class="material-symbols-outlined text-green-600 shrink-0">check_circle</span>
            <span class="font-body-sm text-body-sm font-semibold text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-stack-sm rounded bg-error-container border border-error/20 flex items-start gap-2 mb-stack-md">
            <span class="material-symbols-outlined text-error shrink-0">error</span>
            <span class="font-body-sm text-body-sm font-semibold text-on-error-container">{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- Page Header & Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-stack-md">
        <div>
            <h2 class="font-display-lg text-display-lg text-primary">Keuangan</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Ringkasan dan manajemen transaksi keuangan institusi.</p>
        </div>
        <div class="flex gap-3">
            <button onclick="openExpenseModal()" class="px-4 py-2 bg-surface-container-lowest border border-outline-variant text-primary rounded-lg font-body-sm font-bold hover:bg-surface-container-high transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">arrow_downward</span> Catat Pengeluaran
            </button>
            <button onclick="openIncomeModal()" class="px-4 py-2 bg-primary text-on-primary rounded-lg font-body-sm font-bold hover:bg-secondary transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">arrow_upward</span> Catat Pemasukan
            </button>
        </div>
    </div>
    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 hover:bg-surface-container-high transition-colors">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-2">SALDO SAAT INI</p>
            <h3 class="font-data-mono text-data-mono text-[24px] font-bold text-primary">Rp {{ number_format($currentBalance, 0, ',', '.') }}</h3>
            <p class="font-body-sm text-body-sm {{ $balanceTrendColor }} mt-2 flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">{{ $balanceTrendIcon }}</span> {{ $balanceTrendText }}
            </p>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 hover:bg-surface-container-high transition-colors">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-2">PEMASUKAN BULAN INI</p>
            <h3 class="font-data-mono text-data-mono text-[24px] font-bold text-primary">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</h3>
            <div class="w-full bg-surface-container-high h-1 mt-4 rounded-full overflow-hidden">
                <div class="bg-secondary-container w-3/4 h-full"></div>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 hover:bg-surface-container-high transition-colors">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-2">PENGELUARAN BULAN INI</p>
            <h3 class="font-data-mono text-data-mono text-[24px] font-bold text-primary">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</h3>
            <div class="w-full bg-surface-container-high h-1 mt-4 rounded-full overflow-hidden">
                <div class="bg-error w-1/2 h-full"></div>
            </div>
        </div>
    </div>
    <!-- Transaction Table Section -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
        <div class="p-4 border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
            <h3 class="font-headline-sm text-headline-sm text-primary">Riwayat Transaksi</h3>
            <div class="flex gap-2">
                <button onclick="openCategoryModal()" class="px-3 py-1.5 border border-outline-variant rounded bg-surface-container-lowest font-body-sm font-bold flex items-center gap-1 hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined text-[16px]">category</span> Kelola Kategori
                </button>
                <div class="relative">
                    <button onclick="toggleFilterDropdown()" class="px-3 py-1.5 border border-outline-variant rounded bg-surface-container-lowest font-body-sm font-bold flex items-center gap-1 hover:bg-surface-container-low transition-colors">
                        <span class="material-symbols-outlined text-[16px]">filter_alt</span> Filter
                    </button>
                    <div id="filter-dropdown" class="absolute right-0 mt-2 w-56 rounded-xl border border-outline-variant bg-surface-container-lowest shadow-lg p-4 z-30 hidden space-y-3 font-body-sm">
                        <form action="{{ route('finance') }}" method="GET" class="space-y-3">
                            @if(request()->filled('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}"/>
                            @endif
                            <div>
                                <label class="block font-bold text-on-surface mb-1">Tipe</label>
                                <select name="type" class="w-full border border-outline-variant rounded px-2 py-1 bg-surface-container-low text-on-surface text-body-sm cursor-pointer">
                                    <option value="">Semua Tipe</option>
                                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-bold text-on-surface mb-1">Kategori</label>
                                <select name="category" class="w-full border border-outline-variant rounded px-2 py-1 bg-surface-container-low text-on-surface text-body-sm cursor-pointer">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex justify-end gap-2 pt-1">
                                <a href="{{ route('finance') }}" class="px-2 py-1 border border-outline text-primary rounded text-xs hover:bg-surface-container-low transition-colors">Reset</a>
                                <button type="submit" class="px-2 py-1 bg-primary text-on-primary rounded text-xs hover:bg-secondary transition-colors">Terapkan</button>
                            </div>
                        </form>
                    </div>
                </div>
                <a href="{{ route('finance.export', request()->query()) }}" class="px-3 py-1.5 border border-outline-variant rounded bg-surface-container-lowest font-body-sm font-bold flex items-center gap-1 hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-outlined text-[16px]">download</span> Unduh CSV
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="p-4 font-label-caps text-label-caps text-on-surface-variant font-bold w-[120px]">TANGGAL</th>
                        <th class="p-4 font-label-caps text-label-caps text-on-surface-variant font-bold w-[150px]">ID TRANSAKSI</th>
                        <th class="p-4 font-label-caps text-label-caps text-on-surface-variant font-bold">DESKRIPSI</th>
                        <th class="p-4 font-label-caps text-label-caps text-on-surface-variant font-bold w-[150px]">KATEGORI</th>
                        <th class="p-4 font-label-caps text-label-caps text-on-surface-variant font-bold w-[150px] text-right">JUMLAH</th>
                        <th class="p-4 font-label-caps text-label-caps text-on-surface-variant font-bold w-[120px] text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody class="font-body-sm">
                    @foreach($transactions as $trx)
                    <tr class="border-b border-outline-variant hover:bg-surface-container-low transition-colors h-[40px]">
                        <td class="p-4 font-data-mono text-data-mono text-on-surface-variant">{{ $trx->date->format('Y-m-d') }}</td>
                        <td class="p-4 font-data-mono text-data-mono text-on-surface-variant">{{ $trx->transaction_id }}</td>
                        <td class="p-4 font-body-md text-body-md font-semibold text-primary">{{ $trx->description }}</td>
                        <td class="p-4 text-on-surface-variant">{{ $trx->category }}</td>
                        <td class="p-4 font-data-mono text-data-mono font-semibold text-right {{ $trx->type == 'expense' ? 'text-error' : 'text-secondary-container' }}">
                            {{ $trx->type == 'expense' ? '-' : '+' }}Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-xl font-label-caps text-label-caps bg-secondary-fixed text-on-secondary-fixed font-bold">Sukses</span>
                        </td>
                    </tr>
                    @endforeach

                    @if($transactions->isEmpty())
                    <tr>
                        <td colspan="6" class="p-8 text-center text-on-surface-variant">Tidak ada transaksi ditemukan.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="p-3 border-t border-outline-variant bg-surface-container-lowest flex justify-between items-center text-on-surface-variant font-body-sm">
            <span>Menampilkan {{ $transactions->firstItem() ?? 0 }} - {{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} transaksi</span>
            <div class="flex gap-2">
                @if($transactions->onFirstPage())
                    <button class="px-3 py-1 border border-outline-variant rounded opacity-50 cursor-not-allowed" disabled>Sebelumnya</button>
                @else
                    <a href="{{ $transactions->appends(request()->query())->previousPageUrl() }}" class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container-low">Sebelumnya</a>
                @endif

                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->appends(request()->query())->nextPageUrl() }}" class="px-3 py-1 border border-outline-variant rounded hover:bg-surface-container-low">Selanjutnya</a>
                @else
                    <button class="px-3 py-1 border border-outline-variant rounded opacity-50 cursor-not-allowed" disabled>Selanjutnya</button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Catat Pemasukan -->
<div id="income-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-md shadow-lg">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary">Catat Pemasukan</h3>
            <button onclick="closeIncomeModal()" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('finance.store') }}" method="POST" class="space-y-4 font-body-sm">
            @csrf
            <input type="hidden" name="type" value="income"/>
            <div>
                <label class="block font-bold text-on-surface mb-1">Deskripsi Pemasukan</label>
                <input type="text" name="description" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary" placeholder="Contoh: Pembayaran Kursus"/>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Kategori</label>
                <select name="category" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary cursor-pointer">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-on-surface mb-1">Jumlah (Rp)</label>
                    <input type="number" name="amount" min="0" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
                </div>
                <div>
                    <label class="block font-bold text-on-surface mb-1">Tanggal</label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeIncomeModal()" class="px-4 py-2 border border-outline text-primary rounded font-bold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded font-bold hover:bg-secondary transition-colors">Simpan Pemasukan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Catat Pengeluaran -->
<div id="expense-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-md shadow-lg">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary">Catat Pengeluaran</h3>
            <button onclick="closeExpenseModal()" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('finance.store') }}" method="POST" class="space-y-4 font-body-sm">
            @csrf
            <input type="hidden" name="type" value="expense"/>
            <div>
                <label class="block font-bold text-on-surface mb-1">Deskripsi Pengeluaran</label>
                <input type="text" name="description" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary" placeholder="Contoh: Pembelian Listrik Bulanan"/>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Kategori</label>
                <select name="category" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary cursor-pointer">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-on-surface mb-1">Jumlah (Rp)</label>
                    <input type="number" name="amount" min="0" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
                </div>
                <div>
                    <label class="block font-bold text-on-surface mb-1">Tanggal</label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeExpenseModal()" class="px-4 py-2 border border-outline text-primary rounded font-bold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded font-bold hover:bg-secondary transition-colors">Simpan Pengeluaran</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Kelola Kategori -->
<div id="category-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-lg shadow-lg">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary">Kelola Kategori Transaksi</h3>
            <button onclick="closeCategoryModal()" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        
        <!-- Add Category Form -->
        <form action="{{ route('finance.categories.store') }}" method="POST" class="mb-6 flex gap-2 font-body-sm">
            @csrf
            <input type="text" name="name" required placeholder="Nama Kategori Baru..." class="flex-1 border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
            <button type="submit" class="bg-primary text-on-primary px-4 py-2 rounded font-bold hover:bg-secondary transition-colors">Tambah</button>
        </form>

        <!-- Categories List -->
        <div class="max-h-60 overflow-y-auto space-y-2 border border-outline-variant rounded-lg p-2 bg-surface-container-low font-body-sm">
            @foreach($categories as $cat)
            <div class="flex justify-between items-center p-2 rounded bg-surface-container-lowest border border-outline-variant hover:bg-surface-container-low transition-colors">
                <!-- Rename Form -->
                <form id="update-category-form-{{ $cat->id }}" action="{{ route('finance.categories.update', $cat->id) }}" method="POST" class="flex-1 flex gap-2 items-center">
                    @csrf
                    @method('PUT')
                    <input type="text" id="cat-input-{{ $cat->id }}" name="name" value="{{ $cat->name }}" required class="border border-transparent bg-transparent rounded px-2 py-1 text-on-surface font-semibold focus:border-outline-variant focus:bg-surface-container-low focus:outline-none w-full transition-all"/>
                    <button type="submit" class="hidden text-secondary-container hover:text-secondary font-bold text-xs">Simpan</button>
                </form>
                <div class="flex items-center gap-2 ml-2">
                    <button onclick="focusCategoryInput({{ $cat->id }})" class="p-1 rounded hover:bg-surface-container-low text-on-surface-variant hover:text-primary transition-colors" title="Rename Kategori">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </button>
                    <!-- Delete Form -->
                    <form action="{{ route('finance.categories.destroy', $cat->id) }}" method="POST" onsubmit="event.preventDefault(); showCustomConfirm(this, 'Apakah Anda yakin ingin menghapus kategori ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1 rounded hover:bg-surface-container-low text-error transition-colors" title="Hapus Kategori">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach

            @if($categories->isEmpty())
            <p class="text-center p-4 text-on-surface-variant">Belum ada kategori ditambahkan.</p>
            @endif
        </div>
        
        <div class="flex justify-end pt-4">
            <button onclick="closeCategoryModal()" class="px-4 py-2 bg-primary text-on-primary rounded font-bold hover:bg-secondary transition-colors">Tutup</button>
        </div>
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

    function openIncomeModal() {
        document.getElementById('income-modal').classList.remove('hidden');
    }
    function closeIncomeModal() {
        document.getElementById('income-modal').classList.add('hidden');
    }
    function openExpenseModal() {
        document.getElementById('expense-modal').classList.remove('hidden');
    }
    function closeExpenseModal() {
        document.getElementById('expense-modal').classList.add('hidden');
    }
    function openCategoryModal() {
        document.getElementById('category-modal').classList.remove('hidden');
    }
    function closeCategoryModal() {
        document.getElementById('category-modal').classList.add('hidden');
    }
    function toggleFilterDropdown() {
        const dd = document.getElementById('filter-dropdown');
        dd.classList.toggle('hidden');
    }
    function focusCategoryInput(catId) {
        const input = document.getElementById('cat-input-' + catId);
        input.focus();
        input.select();
        // Show hidden save button inside the form
        const form = document.getElementById('update-category-form-' + catId);
        const saveBtn = form.querySelector('button[type="submit"]');
        if (saveBtn) {
            saveBtn.classList.remove('hidden');
        }
    }

    // Close dropdown on click outside
    window.addEventListener('click', function(e) {
        const dd = document.getElementById('filter-dropdown');
        if (dd && !dd.contains(e.target) && !e.target.closest('button[onclick="toggleFilterDropdown()"]')) {
            dd.classList.add('hidden');
        }
    });
</script>
@endpush
