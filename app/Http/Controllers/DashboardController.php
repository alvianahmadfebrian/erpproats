<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Transaction;
use App\Models\Employee;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard summary stats and activity feed.
     */
    public function index()
    {
        // 1. Total Stok
        $totalStock = Inventory::sum('stock');

        // 2. Pendapatan Bulan Ini (income)
        $currentMonthIncome = Transaction::where('type', 'income')
            ->whereMonth('date', date('m'))
            ->whereYear('date', date('Y'))
            ->sum('amount');

        // 3. Total Pengeluaran (expense)
        $currentMonthExpense = Transaction::where('type', 'expense')
            ->whereMonth('date', date('m'))
            ->whereYear('date', date('Y'))
            ->sum('amount');

        // 4. Karyawan Aktif
        $activeEmployeesCount = Employee::count();

        // 5. Status Inventaris Kritis (stock <= 5)
        $criticalStockItems = Inventory::where('stock', '<=', 5)->orderBy('stock')->get();

        // 6. Aktivitas Terakhir (dynamically loaded warnings and recent transactions)
        $activities = [];

        // Low stock alerts
        $lowStockItems = Inventory::where('stock', '<=', 3)->orderBy('stock')->take(2)->get();
        foreach ($lowStockItems as $item) {
            $activities[] = [
                'type' => 'warning',
                'title' => 'Stok Rendah: ' . $item->name,
                'desc' => 'Tersisa ' . $item->stock . ' unit di gudang utama.',
                'time' => 'Sistem Alert',
                'icon' => 'warning',
                'bg_class' => 'bg-error-container/20 text-error',
            ];
        }

        // Recent transactions
        $recentTransactions = Transaction::orderBy('date', 'desc')->orderBy('created_at', 'desc')->take(3)->get();
        foreach ($recentTransactions as $trx) {
            $isIncome = $trx->type == 'income';
            $activities[] = [
                'type' => $isIncome ? 'income' : 'expense',
                'title' => $isIncome ? 'Pembayaran Diterima' : 'Pengeluaran Terdaftar',
                'desc' => $trx->description . ' sebesar Rp ' . number_format($trx->amount, 0, ',', '.'),
                'time' => date('d M Y', strtotime($trx->date)),
                'icon' => $isIncome ? 'receipt_long' : 'payments',
                'bg_class' => $isIncome ? 'bg-surface-container-high text-on-surface' : 'bg-error-container/20 text-error',
            ];
        }

        // Keep top 4 activity feed entries
        $activities = array_slice($activities, 0, 4);

        // 7. Last 6 months financial trend data
        $monthLabels = [];
        $incomeData = [];
        $expenseData = [];

        for ($i = 5; $i >= 0; $i--) {
            $time = strtotime("-$i months");
            $monthLabels[] = date('M', $time);
            $year = date('Y', $time);
            $month = date('m', $time);

            $incomeData[] = Transaction::where('type', 'income')
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->sum('amount');

            $expenseData[] = Transaction::where('type', 'expense')
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->sum('amount');
        }

        // Prevent division by zero, set safe fallback maximum
        $maxVal = max(array_merge($incomeData, $expenseData, [1000000]));

        return view('dashboard', compact(
            'totalStock',
            'currentMonthIncome',
            'currentMonthExpense',
            'activeEmployeesCount',
            'criticalStockItems',
            'activities',
            'monthLabels',
            'incomeData',
            'expenseData',
            'maxVal'
        ));
    }
}
