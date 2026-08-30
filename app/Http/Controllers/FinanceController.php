<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinanceController extends Controller
{
    /**
     * Display transactions list with search, filters, and dynamic categories.
     */
    public function index(Request $request)
    {
        $query = Transaction::query();

        // Search description or TRX-ID
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        // Type filter (income/expense)
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Paginate results
        $transactions = $query->orderBy('date', 'desc')->paginate(10);

        // Fetch dynamic categories
        $categories = TransactionCategory::orderBy('name')->get();

        // Dynamic stats metrics
        $allTransactions = Transaction::all();
        $totalIncome = $allTransactions->where('type', 'income')->sum('amount');
        $totalExpense = $allTransactions->where('type', 'expense')->sum('amount');

        // Dynamic balance calculated purely from database transactions
        $currentBalance = $totalIncome - $totalExpense;

        $monthlyIncome = $allTransactions->where('type', 'income')->sum('amount');
        $monthlyExpense = $allTransactions->where('type', 'expense')->sum('amount');

        // Calculate monthly percentage balance change trend
        $lastMonthIncome = Transaction::where('type', 'income')
            ->whereMonth('date', date('m', strtotime('-1 month')))
            ->whereYear('date', date('Y', strtotime('-1 month')))
            ->sum('amount');
        $lastMonthExpense = Transaction::where('type', 'expense')
            ->whereMonth('date', date('m', strtotime('-1 month')))
            ->whereYear('date', date('Y', strtotime('-1 month')))
            ->sum('amount');
        $lastMonthBalance = $lastMonthIncome - $lastMonthExpense;

        $currentMonthBalance = $monthlyIncome - $monthlyExpense;

        if ($lastMonthBalance == 0) {
            if ($currentMonthBalance == 0) {
                $balanceTrendVal = 0.0;
            } elseif ($currentMonthBalance > 0) {
                $balanceTrendVal = 100.0;
            } else {
                $balanceTrendVal = -100.0;
            }
        } else {
            $balanceTrendVal = (($currentMonthBalance - $lastMonthBalance) / abs($lastMonthBalance)) * 100;
        }

        $balanceTrendIsPositive = $balanceTrendVal >= 0;
        $balanceTrendText = ($balanceTrendIsPositive ? '+' : '') . number_format($balanceTrendVal, 1, ',', '.') . '% dari bulan lalu';
        $balanceTrendIcon = $balanceTrendIsPositive ? 'trending_up' : 'trending_down';
        $balanceTrendColor = $balanceTrendIsPositive ? 'text-secondary-container' : 'text-error';

        return view('finance', compact(
            'transactions',
            'categories',
            'currentBalance',
            'monthlyIncome',
            'monthlyExpense',
            'balanceTrendText',
            'balanceTrendIcon',
            'balanceTrendColor'
        ));
    }

    /**
     * Store new transaction.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:income,expense',
            'description' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        // Generate unique TRX ID (TRX-xxxxx)
        do {
            $randomNum = mt_rand(10000, 99999);
            $trxId = 'TRX-' . $randomNum;
        } while (Transaction::where('transaction_id', $trxId)->exists());

        $validated['transaction_id'] = $trxId;

        Transaction::create($validated);

        return redirect()->route('finance')->with('success', 'Transaksi berhasil dicatat.');
    }

    /**
     * Store new category.
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:transaction_categories,name|max:255',
        ]);

        TransactionCategory::create($validated);

        return redirect()->route('finance')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Update/Rename category.
     */
    public function updateCategory(Request $request, $id)
    {
        $category = TransactionCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|unique:transaction_categories,name,' . $id . '|max:255',
        ]);

        $oldName = $category->name;
        $newName = $validated['name'];

        $category->update($validated);

        // Cascade rename to existing transactions using this category name
        Transaction::where('category', $oldName)->update(['category' => $newName]);

        return redirect()->route('finance')->with('success', 'Kategori berhasil diubah.');
    }

    /**
     * Delete category.
     */
    public function destroyCategory($id)
    {
        $category = TransactionCategory::findOrFail($id);

        // Check if category is used by any transactions
        if (Transaction::where('category', $category->name)->exists()) {
            return redirect()->route('finance')->withErrors(['category' => 'Kategori ini tidak dapat dihapus karena sedang digunakan oleh transaksi aktif.']);
        }

        $category->delete();

        return redirect()->route('finance')->with('success', 'Kategori berhasil dihapus.');
    }

    /**
     * Export transaction list as downloadable CSV.
     */
    public function export(Request $request)
    {
        $query = Transaction::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $transactions = $query->orderBy('date', 'desc')->get();

        $response = new StreamedResponse(function () use ($transactions) {
            $handle = fopen('php://output', 'w');
            
            fputcsv($handle, ['Tanggal', 'ID Transaksi', 'Deskripsi', 'Kategori', 'Jumlah', 'Tipe', 'Status']);

            foreach ($transactions as $trx) {
                $amountSign = ($trx->type == 'expense' ? '-' : '+');
                fputcsv($handle, [
                    $trx->date->format('Y-m-d'),
                    $trx->transaction_id,
                    $trx->description,
                    $trx->category,
                    $amountSign . 'Rp ' . number_format($trx->amount, 0, ',', '.'),
                    $trx->type == 'income' ? 'Pemasukan' : 'Pengeluaran',
                    'Sukses'
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="finance_export.csv"',
        ]);

        return $response;
    }
}
