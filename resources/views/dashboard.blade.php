@extends('layouts.app')

@section('title', 'Overview - Proats Music Center')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 w-full">
    <!-- Hero Header -->
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-2">
        <div>
            <p class="font-body-sm text-body-sm text-on-surface-variant tracking-wide uppercase">Dashboard</p>
            <h2 class="font-display-lg text-display-lg text-primary mt-1">Selamat datang 👋</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Ringkasan performa Proats Music Center bulan ini.</p>
        </div>
        <div class="flex items-center gap-2 px-3 py-1.5 bg-surface-container rounded-full border border-outline-variant text-on-surface-variant font-body-sm">
            <span class="material-symbols-outlined text-[16px]">calendar_today</span>
            <span>{{ date('d M Y') }}</span>
        </div>
    </div>

    <!-- Stat Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card: Total Stok -->
        <a href="{{ route('inventory') }}" class="group relative bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 hover:shadow-lg hover:border-secondary/40 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-secondary/5 to-transparent rounded-bl-full"></div>
            <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center mb-3 group-hover:bg-secondary/20 transition-colors">
                <span class="material-symbols-outlined text-secondary text-[22px]">inventory_2</span>
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">Total Stok</p>
            <p class="font-display-lg text-display-lg text-primary leading-none">{{ number_format($totalStock) }}</p>
            <div class="flex items-center gap-1 mt-3 font-body-sm text-secondary opacity-0 group-hover:opacity-100 transition-opacity">
                <span>Lihat inventaris</span>
                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </div>
        </a>

        <!-- Card: Pendapatan Bulan Ini -->
        <a href="{{ route('finance') }}" class="group relative bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 hover:shadow-lg hover:border-secondary/40 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-green-500/5 to-transparent rounded-bl-full"></div>
            <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center mb-3 group-hover:bg-green-500/20 transition-colors">
                <span class="material-symbols-outlined text-green-600 text-[22px]">trending_up</span>
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">Pemasukan</p>
            <p class="font-display-lg text-display-lg text-primary leading-none">
                <span class="font-body-md text-body-md text-on-surface-variant align-top">Rp</span>
                {{ number_format($currentMonthIncome, 0, ',', '.') }}
            </p>
            <div class="flex items-center gap-1 mt-3 font-body-sm text-green-600">
                <span class="material-symbols-outlined text-[14px]">arrow_upward</span>
                <span>Bulan berjalan</span>
            </div>
        </a>

        <!-- Card: Total Pengeluaran -->
        <a href="{{ route('finance') }}" class="group relative bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 hover:shadow-lg hover:border-secondary/40 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-red-500/5 to-transparent rounded-bl-full"></div>
            <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center mb-3 group-hover:bg-red-500/20 transition-colors">
                <span class="material-symbols-outlined text-error text-[22px]">trending_down</span>
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">Pengeluaran</p>
            <p class="font-display-lg text-display-lg text-primary leading-none">
                <span class="font-body-md text-body-md text-on-surface-variant align-top">Rp</span>
                {{ number_format($currentMonthExpense, 0, ',', '.') }}
            </p>
            <div class="flex items-center gap-1 mt-3 font-body-sm text-error">
                <span class="material-symbols-outlined text-[14px]">arrow_downward</span>
                <span>Bulan berjalan</span>
            </div>
        </a>

        <!-- Card: Karyawan Aktif -->
        <a href="{{ route('hr') }}" class="group relative bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 hover:shadow-lg hover:border-secondary/40 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-violet-500/5 to-transparent rounded-bl-full"></div>
            <div class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center mb-3 group-hover:bg-violet-500/20 transition-colors">
                <span class="material-symbols-outlined text-violet-600 text-[22px]">groups</span>
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">Karyawan Aktif</p>
            <p class="font-display-lg text-display-lg text-primary leading-none">{{ $activeEmployeesCount }}</p>
            <div class="flex items-center gap-1 mt-3 font-body-sm text-violet-600 opacity-0 group-hover:opacity-100 transition-opacity">
                <span>Kelola HR</span>
                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
            </div>
        </a>
    </div>

    <!-- Charts and Activity Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Financial Trend Chart -->
        <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 flex flex-col">
            <div class="flex justify-between items-start mb-1">
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-primary">Tren Keuangan</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant mt-0.5">Pemasukan vs Pengeluaran 6 bulan terakhir</p>
                </div>
                <div class="flex items-center gap-4 font-body-sm">
                    <div class="flex items-center gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-secondary"></div>
                        <span class="text-on-surface-variant">Pemasukan</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-error"></div>
                        <span class="text-on-surface-variant">Pengeluaran</span>
                    </div>
                </div>
            </div>

            @php
                $maxVal = max(array_merge($incomeData, $expenseData, [1000000]));
            @endphp

            <!-- Bar Chart -->
            <div class="flex-1 flex items-end gap-3 mt-6 min-h-[220px] px-2">
                @foreach($monthLabels as $index => $label)
                <div class="flex-1 flex flex-col items-center gap-1">
                    <div class="w-full flex items-end justify-center gap-1" style="height: 180px;">
                        <!-- Income Bar -->
                        <div class="w-[45%] rounded-t-lg bg-gradient-to-t from-secondary to-secondary/70 transition-all duration-500 hover:from-secondary hover:to-secondary/90 relative group cursor-default"
                             style="height: {{ max($incomeData[$index] / $maxVal * 100, 2) }}%;">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-primary text-on-primary text-[10px] px-2 py-0.5 rounded-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none font-data-mono shadow-lg">
                                Rp {{ number_format($incomeData[$index], 0, ',', '.') }}
                            </div>
                        </div>
                        <!-- Expense Bar -->
                        <div class="w-[45%] rounded-t-lg bg-gradient-to-t from-error to-error/60 transition-all duration-500 hover:from-error hover:to-error/80 relative group cursor-default"
                             style="height: {{ max($expenseData[$index] / $maxVal * 100, 2) }}%;">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-primary text-on-primary text-[10px] px-2 py-0.5 rounded-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none font-data-mono shadow-lg">
                                Rp {{ number_format($expenseData[$index], 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <span class="text-[11px] font-label-caps font-bold {{ $index == 5 ? 'text-secondary' : 'text-on-surface-variant' }} mt-1">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Activity Feed -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-headline-sm text-headline-sm text-primary">Aktivitas Terakhir</h3>
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse" title="Live"></span>
            </div>
            <div class="flex-1 overflow-y-auto space-y-1 font-body-sm">
                @foreach($activities as $act)
                <div class="flex gap-3 items-start p-3 rounded-xl hover:bg-surface-container-low transition-colors">
                    <div class="w-9 h-9 rounded-xl {{ $act['bg_class'] }} flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[18px]">{{ $act['icon'] }}</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="font-body-sm font-bold text-primary truncate">{{ $act['title'] }}</p>
                        <p class="text-on-surface-variant mt-0.5 text-[11px] leading-relaxed line-clamp-2">{{ $act['desc'] }}</p>
                        <span class="font-label-caps text-[10px] text-outline mt-1 block">{{ $act['time'] }}</span>
                    </div>
                </div>
                @endforeach

                @if(empty($activities))
                <div class="flex flex-col items-center justify-center h-full py-8 text-on-surface-variant">
                    <span class="material-symbols-outlined text-[40px] text-outline-variant mb-2">inbox</span>
                    <p class="font-body-sm">Tidak ada aktivitas baru.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Critical Stock Section -->
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden">
        <div class="p-5 border-b border-outline-variant flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-error/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-error text-[20px]">notification_important</span>
                </div>
                <div>
                    <h3 class="font-headline-sm text-headline-sm text-primary">Status Inventaris Kritis</h3>
                    <p class="font-body-sm text-body-sm text-on-surface-variant">Barang dengan stok ≤ 5 unit</p>
                </div>
            </div>
            <a href="{{ route('inventory') }}" class="px-3 py-1.5 bg-surface-container rounded-lg border border-outline-variant text-on-surface-variant font-body-sm font-bold flex items-center gap-1 hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                Buka Inventaris
            </a>
        </div>

        @if($criticalStockItems->isEmpty())
        <div class="flex flex-col items-center justify-center py-12 text-on-surface-variant">
            <div class="w-14 h-14 rounded-2xl bg-green-500/10 flex items-center justify-center mb-3">
                <span class="material-symbols-outlined text-green-600 text-[28px]">verified</span>
            </div>
            <p class="font-headline-sm text-headline-sm text-primary mb-1">Semua Stok Aman</p>
            <p class="font-body-sm text-body-sm text-on-surface-variant">Tidak ada barang dengan stok kritis saat ini.</p>
        </div>
        @else
        <div class="divide-y divide-outline-variant">
            @foreach($criticalStockItems as $item)
            <div class="flex items-center justify-between px-5 py-3.5 hover:bg-surface-container-low transition-colors group">
                <div class="flex items-center gap-4 min-w-0">
                    <div class="w-10 h-10 rounded-xl {{ $item->stock <= 2 ? 'bg-error/10' : 'bg-amber-500/10' }} flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined {{ $item->stock <= 2 ? 'text-error' : 'text-amber-600' }} text-[20px]">{{ $item->stock == 0 ? 'error' : 'warning' }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="font-body-md font-semibold text-primary truncate">{{ $item->name }}</p>
                        <p class="font-body-sm text-on-surface-variant">{{ $item->item_id }} · {{ $item->category }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 shrink-0">
                    <div class="text-right">
                        <p class="font-data-mono text-data-mono {{ $item->stock == 0 ? 'text-error' : 'text-amber-600' }} font-bold text-lg">{{ $item->stock }}</p>
                        <p class="font-body-sm text-on-surface-variant text-[10px]">sisa unit</p>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-label-caps text-[10px] font-bold {{ $item->stock == 0 ? 'bg-error/10 text-error' : ($item->stock <= 2 ? 'bg-error/10 text-error' : 'bg-amber-500/10 text-amber-700') }}">
                        {{ $item->stock == 0 ? 'Habis' : ($item->stock <= 2 ? 'Kritis' : 'Rendah') }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
