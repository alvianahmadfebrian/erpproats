@extends('layouts.app')

@section('title', 'Keuangan - Proats Music Center')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 w-full pb-12">
    <!-- Alerts & Errors -->
    @if (session('success'))
        <div class="p-4 rounded-2xl bg-green-50 border border-green-200 flex items-start gap-3 mb-4 animate-fade-in shadow-sm">
            <span class="material-symbols-outlined text-green-600 shrink-0">check_circle</span>
            <span class="font-body-sm text-body-sm font-semibold text-green-800">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-error-container border border-error/20 flex items-start gap-3 mb-4 animate-fade-in shadow-sm">
            <span class="material-symbols-outlined text-error shrink-0">error</span>
            <span class="font-body-sm text-body-sm font-semibold text-on-error-container">{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- Page Header & Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
            <p class="font-body-sm text-body-sm text-on-surface-variant tracking-wide uppercase">Finance</p>
            <h2 class="font-display-lg text-display-lg text-primary mt-1">Keuangan</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Ringkasan dan manajemen transaksi keuangan institusi secara transparan.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button onclick="openExpenseModal()" class="bg-surface-container-lowest border border-outline-variant px-4 py-2.5 rounded-full font-body-sm font-semibold text-rose-600 hover:bg-rose-50 transition-colors flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">arrow_downward</span> Catat Pengeluaran
            </button>
            <button onclick="openIncomeModal()" class="bg-indigo-600 text-white px-5 py-2.5 rounded-full font-body-sm font-semibold hover:bg-indigo-700 transition-colors flex items-center gap-2 shadow-md hover:shadow-lg">
                <span class="material-symbols-outlined text-[18px]">arrow_upward</span> Catat Pemasukan
            </button>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card: Saldo Saat Ini -->
        <div class="group relative bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 hover:shadow-lg hover:border-emerald-500/40 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-emerald-500/5 to-transparent rounded-bl-full"></div>
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-3 group-hover:bg-emerald-500/20 transition-colors">
                <span class="material-symbols-outlined text-emerald-600 text-[22px]">account_balance_wallet</span>
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">SALDO SAAT INI</p>
            <h3 class="font-data-mono text-data-mono text-[24px] font-bold text-primary">Rp {{ number_format($currentBalance, 0, ',', '.') }}</h3>
            <p class="font-body-sm text-body-sm {{ $balanceTrendColor }} mt-3 flex items-center gap-1">
                <span class="material-symbols-outlined text-[16px]">{{ $balanceTrendIcon }}</span> {{ $balanceTrendText }}
            </p>
        </div>

        <!-- Card: Pemasukan Bulan Ini -->
        <div class="group relative bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 hover:shadow-lg hover:border-indigo-500/40 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-indigo-500/5 to-transparent rounded-bl-full"></div>
            <div class="w-10 h-10 rounded-xl bg-indigo-500/10 flex items-center justify-center mb-3 group-hover:bg-indigo-500/20 transition-colors">
                <span class="material-symbols-outlined text-indigo-600 text-[22px]">trending_up</span>
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">PEMASUKAN BULAN INI</p>
            <h3 class="font-data-mono text-data-mono text-[24px] font-bold text-primary">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</h3>
            <div class="w-full bg-surface-container-high h-1.5 mt-4 rounded-full overflow-hidden">
                <div class="bg-indigo-600 w-3/4 h-full rounded-full"></div>
            </div>
        </div>

        <!-- Card: Pengeluaran Bulan Ini -->
        <div class="group relative bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 hover:shadow-lg hover:border-rose-500/40 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-rose-500/5 to-transparent rounded-bl-full"></div>
            <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center mb-3 group-hover:bg-rose-500/20 transition-colors">
                <span class="material-symbols-outlined text-rose-600 text-[22px]">trending_down</span>
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">PENGELUARAN BULAN INI</p>
            <h3 class="font-data-mono text-data-mono text-[24px] font-bold text-primary">Rp {{ number_format($monthlyExpense, 0, ',', '.') }}</h3>
            <div class="w-full bg-surface-container-high h-1.5 mt-4 rounded-full overflow-hidden">
                <div class="bg-rose-600 w-1/2 h-full rounded-full"></div>
            </div>
        </div>
    </div>

    <!-- Transaction Table Section -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden shadow-sm flex flex-col">
        <div class="p-4 border-b border-outline-variant flex flex-col sm:flex-row justify-between items-start sm:items-center bg-surface-container-low/40 gap-4">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600">history</span>
                Riwayat Transaksi
            </h3>
            <div class="flex flex-wrap items-center gap-2">
                <button onclick="openCategoryModal()" class="px-4 py-2 border border-outline-variant rounded-full bg-surface-container-lowest font-body-sm font-semibold text-on-surface-variant hover:bg-surface-container-high transition-colors flex items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">category</span> Kelola Kategori
                </button>
                <div class="relative">
                    <button onclick="toggleFilterDropdown()" class="px-4 py-2 border border-outline-variant rounded-full bg-surface-container-lowest font-body-sm font-semibold text-on-surface-variant hover:bg-surface-container-high transition-colors flex items-center gap-1.5 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">filter_alt</span> Filter
                    </button>
                    <div id="filter-dropdown" class="absolute right-0 mt-2 w-64 rounded-2xl border border-outline-variant bg-surface-container-lowest shadow-xl p-5 z-30 hidden space-y-4 font-body-sm backdrop-blur-sm animate-scale-up">
                        <form action="{{ route('finance') }}" method="GET" class="space-y-4">
                            @if(request()->filled('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}"/>
                            @endif
                            <div>
                                <label class="block font-semibold text-on-surface mb-1.5">Tipe</label>
                                <select name="type" class="w-full border border-outline-variant rounded-xl px-3 py-2 bg-surface-container-low text-on-surface text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer">
                                    <option value="">Semua Tipe</option>
                                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Pemasukan</option>
                                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-on-surface mb-1.5">Kategori</label>
                                <select name="category" class="w-full border border-outline-variant rounded-xl px-3 py-2 bg-surface-container-low text-on-surface text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer">
                                    <option value="">Semua Kategori</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->name }}" {{ request('category') == $cat->name ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex justify-end gap-2 pt-2 border-t border-outline-variant">
                                <a href="{{ route('finance') }}" class="px-4 py-2 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full text-xs font-semibold hover:bg-surface-container-low transition-colors">Reset</a>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-full text-xs font-semibold hover:bg-indigo-700 transition-colors shadow-sm">Terapkan</button>
                            </div>
                        </form>
                    </div>
                </div>
                <a href="{{ route('finance.export', request()->query()) }}" class="px-4 py-2 border border-outline-variant rounded-full bg-surface-container-lowest font-body-sm font-semibold text-on-surface-variant hover:bg-surface-container-high transition-colors flex items-center gap-1.5 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">download</span> Unduh CSV
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-surface-container-low/40 border-b border-outline-variant">
                        <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold w-[130px]">TANGGAL</th>
                        <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold">DESKRIPSI / ID TRANSAKSI</th>
                        <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold w-[180px]">KATEGORI</th>
                        <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold w-[180px] text-right">JUMLAH</th>
                        <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold w-[130px] text-center">STATUS</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/60">
                    @foreach($transactions as $trx)
                    <tr class="group/row hover:bg-surface-container-low/30 transition-all duration-200">
                        <td class="py-4 px-5 font-data-mono text-data-mono text-on-surface-variant/80">{{ $trx->date->format('Y-m-d') }}</td>
                        <td class="py-4 px-5">
                            <div class="flex flex-col min-w-0">
                                <span class="font-body-md font-semibold text-primary truncate">{{ $trx->description }}</span>
                                <span class="font-data-mono text-[10px] text-on-surface-variant/70 mt-0.5">{{ $trx->transaction_id }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-5 text-on-surface-variant font-body-sm">{{ $trx->category }}</td>
                        <td class="py-4 px-5 font-data-mono text-data-mono font-bold text-right text-base {{ $trx->type == 'expense' ? 'text-rose-600' : 'text-emerald-600' }}">
                            {{ $trx->type == 'expense' ? '-' : '+' }}Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </td>
                        <td class="py-4 px-5 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-label-caps text-[10px] font-bold bg-green-500/10 text-green-700">Sukses</span>
                        </td>
                    </tr>
                    @endforeach

                    @if($transactions->isEmpty())
                    <tr>
                        <td colspan="5" class="py-12 text-center text-on-surface-variant font-body-md">
                            <div class="flex flex-col items-center justify-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-[40px] text-outline-variant mb-2">receipt_long</span>
                                <p>Tidak ada transaksi ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <div class="p-4 border-t border-outline-variant bg-surface-container-low/40 flex justify-between items-center text-on-surface-variant font-body-sm">
            <span>Menampilkan {{ $transactions->firstItem() ?? 0 }} - {{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} transaksi</span>
            <div class="flex gap-2">
                @if($transactions->onFirstPage())
                    <button class="px-4 py-2 border border-outline-variant rounded-full opacity-50 cursor-not-allowed text-xs font-semibold" disabled>Sebelumnya</button>
                @else
                    <a href="{{ $transactions->appends(request()->query())->previousPageUrl() }}" class="px-4 py-2 border border-outline-variant rounded-full text-xs font-semibold hover:bg-surface-container-low transition-colors">Sebelumnya</a>
                @endif

                @if($transactions->hasMorePages())
                    <a href="{{ $transactions->appends(request()->query())->nextPageUrl() }}" class="px-4 py-2 border border-outline-variant rounded-full text-xs font-semibold hover:bg-surface-container-low transition-colors">Selanjutnya</a>
                @else
                    <button class="px-4 py-2 border border-outline-variant rounded-full opacity-50 cursor-not-allowed text-xs font-semibold" disabled>Selanjutnya</button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Catat Pemasukan -->
<div id="income-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-md shadow-2xl animate-scale-up">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600">arrow_upward</span>
                Catat Pemasukan
            </h3>
            <button onclick="closeIncomeModal()" class="p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined block">close</span>
            </button>
        </div>
        <form action="{{ route('finance.store') }}" method="POST" class="space-y-4 font-body-sm">
            @csrf
            <input type="hidden" name="type" value="income"/>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Deskripsi Pemasukan</label>
                <input type="text" name="description" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all" placeholder="Contoh: Pembayaran Kursus Piano"/>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Kategori</label>
                <select name="category" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer transition-all">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5">Jumlah (Rp)</label>
                    <input type="number" name="amount" min="0" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
                </div>
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5">Tanggal</label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
                </div>
            </div>
            <div class="flex justify-end gap-2.5 pt-4 border-t border-outline-variant">
                <button type="button" onclick="closeIncomeModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors shadow-md">Simpan Pemasukan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Catat Pengeluaran -->
<div id="expense-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-md shadow-2xl animate-scale-up">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-rose-600">arrow_downward</span>
                Catat Pengeluaran
            </h3>
            <button onclick="closeExpenseModal()" class="p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined block">close</span>
            </button>
        </div>
        <form action="{{ route('finance.store') }}" method="POST" class="space-y-4 font-body-sm">
            @csrf
            <input type="hidden" name="type" value="expense"/>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Deskripsi Pengeluaran</label>
                <input type="text" name="description" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all" placeholder="Contoh: Pembelian Listrik Bulanan"/>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Kategori</label>
                <select name="category" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer transition-all">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5">Jumlah (Rp)</label>
                    <input type="number" name="amount" min="0" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
                </div>
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5">Tanggal</label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
                </div>
            </div>
            <div class="flex justify-end gap-2.5 pt-4 border-t border-outline-variant">
                <button type="button" onclick="closeExpenseModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors shadow-md">Simpan Pengeluaran</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Kelola Kategori -->
<div id="category-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-lg shadow-2xl animate-scale-up">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600">category</span>
                Kelola Kategori Transaksi
            </h3>
            <button onclick="closeCategoryModal()" class="p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined block">close</span>
            </button>
        </div>
        
        <!-- Add Category Form -->
        <form action="{{ route('finance.categories.store') }}" method="POST" class="mb-6 flex gap-2 font-body-sm">
            @csrf
            <input type="text" name="name" required placeholder="Nama Kategori Baru..." class="flex-1 border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
            <button type="submit" class="bg-indigo-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-indigo-700 transition-colors shadow-md">Tambah</button>
        </form>

        <!-- Categories List -->
        <div class="max-h-60 overflow-y-auto space-y-2 border border-outline-variant rounded-2xl p-3 bg-surface-container-low font-body-sm shadow-inner">
            @foreach($categories as $cat)
            <div class="flex justify-between items-center p-2 rounded-xl bg-surface-container-lowest border border-outline-variant hover:bg-surface-container-low/50 transition-all duration-200">
                <!-- Rename Form -->
                <form id="update-category-form-{{ $cat->id }}" action="{{ route('finance.categories.update', $cat->id) }}" method="POST" class="flex-1 flex gap-2 items-center min-w-0">
                    @csrf
                    @method('PUT')
                    <input type="text" id="cat-input-{{ $cat->id }}" name="name" value="{{ $cat->name }}" required class="border border-transparent bg-transparent rounded-lg px-2 py-1 text-on-surface font-semibold focus:border-outline-variant focus:bg-surface-container-low focus:outline-none w-full transition-all text-sm"/>
                    <button type="submit" class="hidden text-indigo-600 hover:text-indigo-700 font-bold text-xs shrink-0 bg-indigo-50 px-2 py-1 rounded-lg shadow-sm">Simpan</button>
                </form>
                <div class="flex items-center gap-1 ml-2 shrink-0">
                    <button onclick="focusCategoryInput({{ $cat->id }})" class="p-1.5 rounded-lg hover:bg-surface-container-low text-on-surface-variant hover:text-indigo-600 transition-all" title="Rename Kategori">
                        <span class="material-symbols-outlined text-[18px] block">edit</span>
                    </button>
                    <!-- Delete Form -->
                    <form action="{{ route('finance.categories.destroy', $cat->id) }}" method="POST" onsubmit="event.preventDefault(); showCustomConfirm(this, 'Apakah Anda yakin ingin menghapus kategori ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-1.5 rounded-lg hover:bg-surface-container-low text-error transition-all" title="Hapus Kategori">
                            <span class="material-symbols-outlined text-[18px] block">delete</span>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach

            @if($categories->isEmpty())
            <p class="text-center p-4 text-on-surface-variant text-sm">Belum ada kategori ditambahkan.</p>
            @endif
        </div>
        
        <div class="flex justify-end pt-4 border-t border-outline-variant mt-4">
            <button onclick="closeCategoryModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Tutup</button>
        </div>
    </div>
</div>

<!-- Custom Confirm Modal -->
<div id="confirm-modal" class="fixed inset-0 z-[100] hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-sm shadow-2xl text-center font-body-sm animate-scale-up">
        <div class="w-14 h-14 bg-error/10 text-error rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-[32px]">delete_forever</span>
        </div>
        <h3 class="font-headline-sm text-headline-sm text-primary mb-2">Konfirmasi</h3>
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
