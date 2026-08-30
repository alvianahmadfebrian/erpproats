<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryCategory;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InventoryController extends Controller
{
    /**
     * Display a listing of the inventory items with search & category filters.
     */
    public function index(Request $request)
    {
        $query = Inventory::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('item_id', 'like', "%{$search}%")
                  ->orWhere('vendor', 'like', "%{$search}%");
            });
        }

        // Category filter
        $activeCategories = [];
        if ($request->has('categories') && is_array($request->input('categories'))) {
            $activeCategories = $request->input('categories');
            $query->whereIn('category', $activeCategories);
        }

        $items = $query->orderBy('item_id')->get();

        // Calculate stats
        $totalStock = $items->sum('stock');
        $lowStockCount = $items->filter(fn($item) => $item->stock > 0 && $item->stock <= 5)->count();
        $outOfStockCount = $items->filter(fn($item) => $item->stock == 0)->count();
        
        // Overstocked if >= 40
        $overstockedCount = $items->filter(fn($item) => $item->stock >= 40)->count();

        // Fetch categories for the CRUD / filter sidebar
        $categories = InventoryCategory::orderBy('name')->get();

        return view('inventory', compact(
            'items',
            'totalStock',
            'lowStockCount',
            'outOfStockCount',
            'overstockedCount',
            'activeCategories',
            'categories'
        ));
    }

    /**
     * Store a newly created item in the database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Generate a random unique item ID (e.g. INS-482)
        do {
            $randomNum = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
            $itemId = 'INS-' . $randomNum;
        } while (Inventory::where('item_id', $itemId)->exists());

        $validated['item_id'] = $itemId;
        $validated['vendor'] = '-';

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/inventory'), $filename);
            $validated['image_path'] = 'uploads/inventory/' . $filename;
        }

        Inventory::create($validated);

        return redirect()->route('inventory')->with('success', 'Item berhasil ditambahkan.');
    }

    /**
     * Update the specified item in the database.
     */
    public function update(Request $request, $id)
    {
        $item = Inventory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $validated['vendor'] = '-';

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old file if it exists
            if ($item->image_path && file_exists(public_path($item->image_path))) {
                @unlink(public_path($item->image_path));
            }

            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/inventory'), $filename);
            $validated['image_path'] = 'uploads/inventory/' . $filename;
        }

        $item->update($validated);

        return redirect()->route('inventory')->with('success', 'Item berhasil diperbarui.');
    }

    /**
     * Remove the specified item from the database.
     */
    public function destroy($id)
    {
        $item = Inventory::findOrFail($id);

        // Delete image file if it exists
        if ($item->image_path && file_exists(public_path($item->image_path))) {
            @unlink(public_path($item->image_path));
        }

        $item->delete();

        return redirect()->route('inventory')->with('success', 'Item berhasil dihapus.');
    }

    /**
     * Export dynamic listing as a downloadable CSV.
     */
    public function export(Request $request)
    {
        $query = Inventory::query();

        // Apply same filters as main table
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('item_id', 'like', "%{$search}%")
                  ->orWhere('vendor', 'like', "%{$search}%");
            });
        }

        if ($request->has('categories') && is_array($request->input('categories'))) {
            $query->whereIn('category', $request->input('categories'));
        }

        $items = $query->orderBy('item_id')->get();

        $response = new StreamedResponse(function () use ($items) {
            $handle = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($handle, ['Item ID', 'Nama Barang', 'Kategori', 'Stok', 'Harga Unit', 'Vendor', 'Status']);

            foreach ($items as $item) {
                fputcsv($handle, [
                    $item->item_id,
                    $item->name,
                    $item->category,
                    $item->stock,
                    number_format($item->price, 2, '.', ''),
                    $item->vendor,
                    $item->status,
                ]);
            }

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="inventory_export.csv"',
        ]);

        return $response;
    }

    /**
     * Store new inventory category.
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:inventory_categories,name|max:255',
        ]);

        InventoryCategory::create($validated);

        return redirect()->route('inventory')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Update/Rename inventory category.
     */
    public function updateCategory(Request $request, $id)
    {
        $category = InventoryCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|unique:inventory_categories,name,' . $id . '|max:255',
        ]);

        $oldName = $category->name;
        $newName = $validated['name'];

        $category->update($validated);

        // Cascade rename to existing inventory items
        Inventory::where('category', $oldName)->update(['category' => $newName]);

        return redirect()->route('inventory')->with('success', 'Kategori berhasil diubah.');
    }

    /**
     * Delete inventory category.
     */
    public function destroyCategory($id)
    {
        $category = InventoryCategory::findOrFail($id);

        // Check if category is used by any inventory items
        if (Inventory::where('category', $category->name)->exists()) {
            return redirect()->route('inventory')->withErrors(['category' => 'Kategori ini tidak dapat dihapus karena masih digunakan oleh beberapa barang.']);
        }

        $category->delete();

        return redirect()->route('inventory')->with('success', 'Kategori berhasil dihapus.');
    }
}
