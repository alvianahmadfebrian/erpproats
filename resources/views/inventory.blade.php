@extends('layouts.app')

@section('title', 'Inventory - Proats Music Center')

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
    <!-- Page Header -->
    <div class="flex justify-between items-end">
        <div>
            <h2 class="font-display-lg text-display-lg text-primary">Inventory Management</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Manage instruments, track stock levels, and coordinate with vendors.</p>
        </div>
        <div class="flex gap-stack-sm">
            <a href="{{ route('inventory.export', request()->query()) }}" class="bg-surface-container-lowest border border-outline px-4 py-2 rounded-lg font-body-sm font-bold text-on-surface hover:bg-surface-container-high transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">download</span> Export
            </a>
            <button onclick="openScanModal()" class="bg-surface-container-lowest border border-outline px-4 py-2 rounded-lg font-body-sm font-bold text-on-surface hover:bg-surface-container-high transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">qr_code_scanner</span> Scan QR
            </button>
            <button onclick="openAddModal()" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-body-sm font-bold hover:bg-primary-container transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add</span> Add Item
            </button>
        </div>
    </div>

    <!-- Stats Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-gutter w-full">
        <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded-xl flex flex-col justify-center shadow-sm">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-2">Total Stok</p>
            <div class="flex items-baseline gap-unit">
                <span class="font-display-lg text-display-lg font-bold text-primary">{{ $totalStock }}</span>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded-xl flex flex-col justify-center shadow-sm">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-2">Low Stock Items</p>
            <div class="flex items-baseline gap-unit">
                <span class="font-display-lg text-display-lg font-bold text-error">{{ $lowStockCount }}</span>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded-xl flex flex-col justify-center shadow-sm">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-2">Out of Stock</p>
            <div class="flex items-baseline gap-unit">
                <span class="font-display-lg text-display-lg font-bold text-error">{{ $outOfStockCount }}</span>
            </div>
        </div>
        <div class="bg-surface-container-lowest border border-outline-variant p-4 rounded-xl flex flex-col justify-center shadow-sm">
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-2">Overstocked Items</p>
            <div class="flex items-baseline gap-unit">
                <span class="font-display-lg text-display-lg font-bold text-primary">{{ $overstockedCount }}</span>
            </div>
        </div>
    </div>

    <!-- Bento Grid Layout -->
    <div class="grid grid-cols-12 gap-gutter">
        <!-- Filters & Controls (Left Column) -->
        <div class="col-span-12 lg:col-span-3 flex flex-col gap-gutter">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-stack-md">
                <h3 class="font-headline-sm text-headline-sm mb-stack-sm border-b border-outline-variant pb-2">Category Filter</h3>
                <form id="filter-form" action="{{ route('inventory') }}" method="GET" class="flex flex-col gap-2 mt-stack-md">
                    @if(request()->filled('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}"/>
                    @endif
                    <label class="flex items-center gap-2 font-body-md cursor-pointer hover:bg-surface-container-low p-1 rounded">
                        <input type="checkbox" id="check-all" class="rounded border-outline-variant text-secondary focus:ring-secondary-container" onchange="toggleAllCategories(this)" {{ empty($activeCategories) ? 'checked' : '' }}/>
                        All Instruments
                    </label>
                    @foreach($categories as $cat)
                    <div class="group flex items-center justify-between gap-1 p-1 rounded hover:bg-surface-container-low transition-colors">
                        <label class="flex items-center gap-2 font-body-md cursor-pointer flex-1 overflow-hidden">
                            <input type="checkbox" name="categories[]" value="{{ $cat->name }}" class="category-checkbox rounded border-outline-variant text-secondary focus:ring-secondary-container" {{ in_array($cat->name, $activeCategories) ? 'checked' : '' }}/>
                            <span class="truncate">{{ $cat->name }}</span>
                        </label>
                        <div class="opacity-0 group-hover:opacity-100 flex items-center gap-0.5 transition-opacity shrink-0">
                            <button type="button" onclick="event.stopPropagation(); openEditCategoryModal({{ $cat->id }}, '{{ addslashes($cat->name) }}')" class="p-0.5 rounded text-on-surface-variant hover:text-primary hover:bg-white/80 transition-colors" title="Rename Kategori">
                                <span class="material-symbols-outlined text-[16px] block">edit</span>
                            </button>
                            <form action="{{ route('inventory.categories.destroy', $cat->id) }}" method="POST" class="inline" onsubmit="event.preventDefault(); showCustomConfirm(this, 'Apakah Anda yakin ingin menghapus kategori ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-0.5 rounded text-error hover:bg-white/80 transition-colors" title="Hapus Kategori">
                                    <span class="material-symbols-outlined text-[16px] block">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </form>
                
                <!-- Add Category Form inside filter card -->
                <form action="{{ route('inventory.categories.store') }}" method="POST" class="mt-4 pt-2 border-t border-outline-variant flex gap-1.5 font-body-sm">
                    @csrf
                    <input type="text" name="name" required placeholder="Tambah kategori..." class="flex-1 border border-outline-variant rounded-lg px-2.5 py-1.5 bg-surface-container-low text-on-surface text-xs focus:outline-none focus:ring-2 focus:ring-secondary"/>
                    <button type="submit" class="bg-primary text-on-primary px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-secondary transition-colors flex items-center justify-center shrink-0" title="Tambah Kategori">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                    </button>
                </form>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-stack-md">
                <h3 class="font-headline-sm text-headline-sm mb-stack-sm border-b border-outline-variant pb-2">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-2 mt-stack-md">
                    <button class="border border-outline-variant rounded-lg p-2 flex flex-col items-center justify-center gap-1 hover:border-secondary hover:text-secondary transition-colors group">
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-secondary transition-colors" data-icon="inventory">inventory</span>
                        <span class="font-label-caps text-label-caps text-center">Audit Stock</span>
                    </button>
                    <button class="border border-outline-variant rounded-lg p-2 flex flex-col items-center justify-center gap-1 hover:border-secondary hover:text-secondary transition-colors group">
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-secondary transition-colors" data-icon="local_shipping">local_shipping</span>
                        <span class="font-label-caps text-label-caps text-center">Vendor Orders</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Main Data Table (Right Column) -->
        <div class="col-span-12 lg:col-span-9 bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col shadow-sm">
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="data-table-header border-b border-outline-variant">
                            <th class="py-3 px-4 font-label-caps text-label-caps text-on-surface-variant font-bold whitespace-nowrap">ID</th>
                            <th class="py-3 px-4 font-label-caps text-label-caps text-on-surface-variant font-bold text-center w-12">QR</th>
                            <th class="py-3 px-4 font-label-caps text-label-caps text-on-surface-variant font-bold">Item Name</th>
                            <th class="py-3 px-4 font-label-caps text-label-caps text-on-surface-variant font-bold">Category</th>
                            <th class="py-3 px-4 font-label-caps text-label-caps text-on-surface-variant font-bold text-right">Current Stock</th>
                            <th class="py-3 px-4 font-label-caps text-label-caps text-on-surface-variant font-bold text-right whitespace-nowrap">Unit Price</th>
                            <th class="py-3 px-4 font-label-caps text-label-caps text-on-surface-variant font-bold">Status</th>
                            <th class="py-3 px-4 font-label-caps text-label-caps text-on-surface-variant font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="font-body-sm">
                        @foreach($items as $item)
                        <tr class="data-table-row border-b border-outline-variant h-[56px] transition-colors">
                            <td class="py-2 px-4 font-data-mono text-data-mono text-on-surface-variant">{{ $item->item_id }}</td>
                            <td class="py-2 px-4 text-center">
                                <button onclick="openQrModal({{ json_encode($item) }})" class="p-1 rounded text-secondary hover:bg-slate-100 transition-colors" title="View QR Code">
                                    <span class="material-symbols-outlined text-[20px] block">qr_code</span>
                                </button>
                            </td>
                            <td class="py-2 px-4 font-body-md text-body-md font-semibold text-primary">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl overflow-hidden border border-outline-variant bg-surface-container-low flex items-center justify-center shrink-0 shadow-sm">
                                        @if($item->image_path && file_exists(public_path($item->image_path)))
                                            <img src="/{{ $item->image_path }}" alt="{{ $item->name }}" class="w-full h-full object-cover"/>
                                        @else
                                            <span class="material-symbols-outlined text-slate-400 text-[20px]">music_note</span>
                                        @endif
                                    </div>
                                    <span class="truncate">{{ $item->name }}</span>
                                </div>
                            </td>
                            <td class="py-2 px-4 text-on-surface-variant">{{ $item->category }}</td>
                            <td class="py-2 px-4 font-data-mono text-data-mono text-right {{ $item->stock == 0 ? 'text-error font-bold' : ($item->stock <= 5 ? 'text-error' : '') }}">{{ $item->stock }}</td>
                            <td class="py-2 px-4 font-data-mono text-data-mono text-right whitespace-nowrap">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="py-2 px-4">
                                @if($item->status == 'Optimal')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-xl font-label-caps text-label-caps bg-secondary-fixed text-on-secondary-fixed font-bold">Optimal</span>
                                @elseif($item->status == 'Low Stock')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-xl font-label-caps text-label-caps bg-error-container text-on-error-container font-bold">Low Stock</span>
                                @elseif($item->status == 'Out of Stock')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-xl font-label-caps text-label-caps bg-error-container text-on-error-container font-bold font-bold">Out of Stock</span>
                                @elseif($item->status == 'Overstocked')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-xl font-label-caps text-label-caps bg-primary-fixed-dim text-on-primary-fixed-variant font-bold">Overstocked</span>
                                @endif
                            </td>
                            <td class="py-2 px-4 text-right">
                                <div class="flex justify-end gap-2 items-center">
                                    <button onclick="openEditModal({{ json_encode($item) }})" class="p-1 rounded hover:bg-surface-variant text-on-surface-variant transition-colors" title="Edit Item">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="event.preventDefault(); showCustomConfirm(this, 'Apakah Anda yakin ingin menghapus item ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded hover:bg-surface-variant text-error transition-colors" title="Delete Item">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        @if($items->isEmpty())
                        <tr>
                            <td colspan="8" class="py-8 text-center text-on-surface-variant">Tidak ada barang inventaris ditemukan.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- Pagination Footer -->
            <div class="p-3 border-t border-outline-variant bg-surface-container-lowest flex justify-between items-center text-on-surface-variant font-body-sm">
                <span>Showing {{ $items->count() }} of {{ $items->count() }} entries</span>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="add-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-md shadow-lg">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary">Add New Item</h3>
            <button onclick="closeAddModal()" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 font-body-sm">
            @csrf
            <div>
                <label class="block font-bold text-on-surface mb-1">Item Name</label>
                <input type="text" name="name" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Category</label>
                <select name="category" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary cursor-pointer">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Product Photo</label>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-outline-variant bg-surface-container-low flex items-center justify-center shrink-0 shadow-sm">
                        <img id="add-image-preview" src="" alt="Preview" class="w-full h-full object-cover hidden"/>
                        <span id="add-image-placeholder" class="material-symbols-outlined text-slate-400 text-[24px]">image</span>
                    </div>
                    <input type="file" name="image" accept="image/*" class="flex-1 font-body-sm text-xs cursor-pointer" onchange="previewImage(this, 'add-image-preview', 'add-image-placeholder')"/>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-on-surface mb-1">Stock</label>
                    <input type="number" name="stock" min="0" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
                </div>
                <div>
                    <label class="block font-bold text-on-surface mb-1">Unit Price (Rp)</label>
                    <input type="number" step="0.01" name="price" min="0" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeAddModal()" class="px-4 py-2 border border-outline text-primary rounded font-bold hover:bg-surface-container-low transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded font-bold hover:bg-secondary transition-colors">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-md shadow-lg">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary">Edit Item</h3>
            <button onclick="closeEditModal()" class="text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="edit-form" action="" method="POST" enctype="multipart/form-data" class="space-y-4 font-body-sm">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-bold text-on-surface mb-1">Item Name</label>
                <input type="text" id="edit-name" name="name" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Category</label>
                <select id="edit-category" name="category" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary cursor-pointer">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-bold text-on-surface mb-1">Product Photo</label>
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-xl overflow-hidden border border-outline-variant bg-surface-container-low flex items-center justify-center shrink-0 shadow-sm">
                        <img id="edit-image-preview" src="" alt="Preview" class="w-full h-full object-cover hidden"/>
                        <span id="edit-image-placeholder" class="material-symbols-outlined text-slate-400 text-[24px]">image</span>
                    </div>
                    <input type="file" name="image" accept="image/*" class="flex-1 font-body-sm text-xs cursor-pointer" onchange="previewImage(this, 'edit-image-preview', 'edit-image-placeholder')"/>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block font-bold text-on-surface mb-1">Stock</label>
                    <input type="number" id="edit-stock" name="stock" min="0" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
                </div>
                <div>
                    <label class="block font-bold text-on-surface mb-1">Unit Price (Rp)</label>
                    <input type="number" step="0.01" id="edit-price" name="price" min="0" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 border border-outline text-primary rounded font-bold hover:bg-surface-container-low transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded font-bold hover:bg-secondary transition-colors">Save Changes</button>
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

<!-- QR Detail Modal -->
<div id="qr-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-sm shadow-lg text-center font-body-sm relative">
        <button onclick="closeQrModal()" class="absolute top-4 right-4 text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
        <h3 class="font-headline-sm text-headline-sm text-primary mb-1">QR Code Barang</h3>
        <p id="qr-item-name" class="font-body-md font-bold text-slate-700 mb-4"></p>
        
        <!-- QR Container (Print Area) -->
        <div id="qr-print-area" class="bg-white p-4 rounded-lg border border-slate-200 inline-block mb-6">
            <img id="qr-image" src="" alt="QR Code" class="w-48 h-48 mx-auto object-contain"/>
            <div id="qr-print-meta" class="mt-2 text-center text-xs text-slate-800 font-bold hidden">
                <p id="qr-meta-id"></p>
                <p id="qr-meta-name"></p>
            </div>
        </div>
        
        <div class="flex justify-center gap-3">
            <button onclick="closeQrModal()" class="px-4 py-2 border border-outline text-primary rounded font-bold hover:bg-surface-container-low transition-colors">Tutup</button>
            <button onclick="printQrCode()" class="px-4 py-2 bg-primary text-on-primary rounded font-bold hover:bg-secondary transition-colors flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">print</span> Cetak QR
            </button>
        </div>
    </div>
</div>

<!-- Live Scanner Modal -->
<div id="scan-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-md shadow-lg font-body-sm relative">
        <button onclick="closeScanModal()" class="absolute top-4 right-4 text-on-surface-variant hover:text-primary transition-colors z-10">
            <span class="material-symbols-outlined">close</span>
        </button>
        <h3 class="font-headline-sm text-headline-sm text-primary mb-2">Scan QR Code Barang</h3>
        <p class="text-on-surface-variant mb-4 text-xs">Posisikan QR Code barang di dalam area pemindaian kamera.</p>
        
        <!-- html5-qrcode reader element -->
        <div class="bg-black rounded-lg overflow-hidden relative border border-slate-300">
            <div id="qr-reader" class="w-full"></div>
        </div>
        
        <div class="flex justify-end gap-3 mt-4">
            <button onclick="closeScanModal()" class="px-4 py-2 border border-outline text-primary rounded font-bold hover:bg-surface-container-low transition-colors">Batal</button>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="edit-category-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-6 w-full max-w-sm shadow-lg font-body-sm relative">
        <button type="button" onclick="closeEditCategoryModal()" class="absolute top-4 right-4 text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined">close</span>
        </button>
        <h3 class="font-headline-sm text-headline-sm text-primary mb-4">Rename Kategori</h3>
        <form id="edit-category-form" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-bold text-on-surface mb-1">Nama Kategori</label>
                <input type="text" id="edit-category-name" name="name" required class="w-full border border-outline-variant rounded px-3 py-2 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary"/>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeEditCategoryModal()" class="px-4 py-2 border border-outline text-primary rounded font-bold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded font-bold hover:bg-secondary transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
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

    function openAddModal() {
        document.getElementById('add-image-preview').src = '';
        document.getElementById('add-image-preview').classList.add('hidden');
        document.getElementById('add-image-placeholder').classList.remove('hidden');
        document.getElementById('add-modal').classList.remove('hidden');
    }
    function closeAddModal() {
        document.getElementById('add-modal').classList.add('hidden');
    }
    function openEditModal(item) {
        document.getElementById('edit-form').action = '/inventory/' + item.id;
        document.getElementById('edit-name').value = item.name;
        document.getElementById('edit-category').value = item.category;
        document.getElementById('edit-stock').value = item.stock;
        document.getElementById('edit-price').value = item.price;
        
        // Show current image if exists
        const preview = document.getElementById('edit-image-preview');
        const placeholder = document.getElementById('edit-image-placeholder');
        if (item.image_path) {
            preview.src = '/' + item.image_path;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
        
        document.getElementById('edit-modal').classList.remove('hidden');
    }
    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }

    function previewImage(input, previewId, placeholderId) {
        const preview = document.getElementById(previewId);
        const placeholder = document.getElementById(placeholderId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '';
            preview.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }

    function submitFilterForm() {
        document.getElementById('filter-form').submit();
    }
    function toggleAllCategories(checkbox) {
        if (checkbox.checked) {
            const checkboxes = document.querySelectorAll('.category-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
            submitFilterForm();
        }
    }

    document.querySelectorAll('.category-checkbox').forEach(cb => {
        cb.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('check-all').checked = false;
            }
            submitFilterForm();
        });
    });

    // QR Code Detail Modal Functions
    function openQrModal(item) {
        document.getElementById('qr-item-name').textContent = item.name;
        document.getElementById('qr-meta-id').textContent = "Item ID: " + item.item_id;
        document.getElementById('qr-meta-name').textContent = "Item Name: " + item.name;
        
        // Generate QR code using REST API
        const qrData = JSON.stringify({ item_id: item.item_id });
        const qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" + encodeURIComponent(qrData);
        document.getElementById('qr-image').src = qrUrl;
        
        document.getElementById('qr-modal').classList.remove('hidden');
    }

    function closeQrModal() {
        document.getElementById('qr-modal').classList.add('hidden');
    }

    function printQrCode() {
        const imgUrl = document.getElementById('qr-image').src;
        const itemName = document.getElementById('qr-item-name').textContent;
        const itemMeta = document.getElementById('qr-meta-id').textContent;
        
        const printWindow = window.open('', '_blank');
        printWindow.document.write(
            '<' + 'html>' +
            '<' + 'head>' +
            '<title>Cetak QR Code</title>' +
            '<style>' +
                'body { font-family: sans-serif; text-align: center; padding: 40px; }' +
                'img { width: 220px; height: 220px; margin-bottom: 15px; }' +
                'h2 { margin: 0; font-size: 16px; font-weight: bold; }' +
                'p { margin: 4px 0 0 0; font-size: 12px; color: #444; }' +
                '@media print { body { padding: 0; } }' +
            '</style>' +
            '</' + 'head>' +
            '<' + 'body>' +
                '<img src="' + imgUrl + '" />' +
                '<h2>' + itemName + '</h2>' +
                '<p>' + itemMeta + '</p>' +
            '</' + 'body>' +
            '</' + 'html>'
        );
        // Append script dynamically to prevent parser premature termination
        const scr = printWindow.document.createElement('script');
        scr.textContent = `
            window.onload = function() {
                window.print();
                setTimeout(() => window.close(), 500);
            }
        `;
        printWindow.document.body.appendChild(scr);
        printWindow.document.close();
    }

    // HTML5-QR Code Scanner Functions
    let html5Qrcode = null;

    function openScanModal() {
        document.getElementById('scan-modal').classList.remove('hidden');
        
        // Initialize HTML5 QR Code library instance
        html5Qrcode = new Html5Qrcode("qr-reader");
        
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        
        // Start scanning with environment-facing camera
        html5Qrcode.start(
            { facingMode: "environment" },
            config,
            (decodedText, decodedResult) => {
                // Parse result
                try {
                    let itemId = decodedText;
                    if (decodedText.startsWith('{')) {
                        const parsed = JSON.parse(decodedText);
                        itemId = parsed.item_id || decodedText;
                    }
                    
                    // Stop camera and close scan modal
                    stopScanner();
                    closeScanModal();
                    
                    // Search in inventory list instantly
                    window.location.href = "/inventory?search=" + encodeURIComponent(itemId);
                } catch(e) {
                    console.error(e);
                    alert("QR Code tidak valid: " + decodedText);
                }
            },
            (errorMessage) => {
                // Verbose debug scanning - omit to avoid noise
            }
        ).catch((err) => {
            console.error("Camera access failed", err);
            alert("Gagal mengakses kamera. Mohon berikan izin kamera.");
            closeScanModal();
        });
    }

    function stopScanner() {
        if (html5Qrcode && html5Qrcode.isScanning) {
            html5Qrcode.stop().then(() => {
                html5Qrcode = null;
            }).catch((err) => console.error("Error stopping html5Qrcode", err));
        }
    }

    function closeScanModal() {
        stopScanner();
        document.getElementById('scan-modal').classList.add('hidden');
    }

    // Category CRUD Helpers
    function openEditCategoryModal(catId, catName) {
        document.getElementById('edit-category-form').action = '/inventory/categories/' + catId;
        document.getElementById('edit-category-name').value = catName;
        document.getElementById('edit-category-modal').classList.remove('hidden');
    }

    function closeEditCategoryModal() {
        document.getElementById('edit-category-modal').classList.add('hidden');
    }
</script>
@endpush
