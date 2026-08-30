<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VendorController extends Controller
{
    /**
     * Display filtered and paginated list of vendors.
     */
    public function index(Request $request)
    {
        $query = Vendor::query();

        // Search name, contact or address
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Paginate list
        $vendors = $query->orderBy('name')->paginate(10);

        // Stats summary calculation
        $allVendors = Vendor::all();
        $totalVendorsCount = $allVendors->count();
        $activeVendorsCount = $allVendors->where('status', 'Aktif')->count();

        // Sum of procurement costs for matching/filtered vendors
        $nilaiPengadaan = $query->sum('procurement_cost');

        return view('vendors', compact(
            'vendors',
            'totalVendorsCount',
            'activeVendorsCount',
            'nilaiPengadaan'
        ));
    }

    /**
     * Store new vendor record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:Alat Musik,Aksesori,Service',
            'contact' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'status' => 'required|string|in:Aktif,Suspen',
            'procurement_cost' => 'required|numeric|min:0',
        ]);

        Vendor::create($validated);

        return redirect()->route('vendors')->with('success', 'Vendor berhasil ditambahkan.');
    }

    /**
     * Update existing vendor.
     */
    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:Alat Musik,Aksesori,Service',
            'contact' => 'required|email|max:255',
            'address' => 'required|string|max:255',
            'status' => 'required|string|in:Aktif,Suspen',
            'procurement_cost' => 'required|numeric|min:0',
        ]);

        $vendor->update($validated);

        return redirect()->route('vendors')->with('success', 'Vendor berhasil diperbarui.');
    }

    /**
     * Delete vendor.
     */
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();

        return redirect()->route('vendors')->with('success', 'Vendor berhasil dihapus.');
    }

    /**
     * Export vendor records as downloadable CSV.
     */
    public function export(Request $request)
    {
        $query = Vendor::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('contact', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $vendors = $query->orderBy('name')->get();

        $response = new StreamedResponse(function () use ($vendors) {
            $handle = fopen('php://output', 'w');
            
            fputcsv($handle, ['Nama Vendor', 'Kategori', 'Kontak', 'Alamat', 'Status', 'Nilai Pengadaan']);

            foreach ($vendors as $v) {
                fputcsv($handle, [
                    $v->name,
                    $v->category,
                    $v->contact,
                    $v->address,
                    $v->status,
                    'Rp ' . number_format($v->procurement_cost, 0, ',', '.')
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="vendors_export.csv"',
        ]);

        return $response;
    }
}
