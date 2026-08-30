@extends('layouts.app')

@section('title', 'Inventory - Proats Music Center')

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

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <p class="font-body-sm text-body-sm text-on-surface-variant tracking-wide uppercase">Operations</p>
            <h2 class="font-display-lg text-display-lg text-primary mt-1">Inventory Management</h2>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Kelola instrumen, lacak tingkat stok, dan koordinasi dengan vendor secara real-time.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('inventory.export', request()->query()) }}" class="bg-surface-container-lowest border border-outline-variant px-4 py-2 rounded-full font-body-sm font-semibold text-on-surface-variant hover:bg-surface-container-high transition-colors flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">download</span> Export
            </a>
            <button onclick="openScanModal()" class="bg-surface-container-lowest border border-outline-variant px-4 py-2 rounded-full font-body-sm font-semibold text-on-surface-variant hover:bg-surface-container-high transition-colors flex items-center gap-2 shadow-sm">
                <span class="material-symbols-outlined text-[18px]">qr_code_scanner</span> Scan QR
            </button>
            <button onclick="openAddModal()" class="bg-indigo-600 text-white px-5 py-2.5 rounded-full font-body-sm font-semibold hover:bg-indigo-700 transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
                <span class="material-symbols-outlined text-[18px]">add</span> Add Item
            </button>
        </div>
    </div>

    <!-- Stats Bento Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 w-full">
        <!-- Card: Total Stok -->
        <div class="group relative bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 hover:shadow-lg hover:border-secondary/40 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-secondary/5 to-transparent rounded-bl-full"></div>
            <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center mb-3 group-hover:bg-secondary/20 transition-colors">
                <span class="material-symbols-outlined text-secondary text-[22px]">inventory_2</span>
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">Total Stok</p>
            <p class="font-display-lg text-display-lg text-primary leading-none font-bold">{{ number_format($totalStock) }}</p>
        </div>

        <!-- Card: Low Stock Items -->
        <div class="group relative bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 hover:shadow-lg hover:border-amber-500/40 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-amber-500/5 to-transparent rounded-bl-full"></div>
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center mb-3 group-hover:bg-amber-500/20 transition-colors">
                <span class="material-symbols-outlined text-amber-600 text-[22px]">warning</span>
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">Low Stock Items</p>
            <p class="font-display-lg text-display-lg text-error leading-none font-bold">{{ number_format($lowStockCount) }}</p>
        </div>

        <!-- Card: Out of Stock -->
        <div class="group relative bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 hover:shadow-lg hover:border-red-500/40 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-red-500/5 to-transparent rounded-bl-full"></div>
            <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center mb-3 group-hover:bg-red-500/20 transition-colors">
                <span class="material-symbols-outlined text-red-600 text-[22px]">error</span>
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">Out of Stock</p>
            <p class="font-display-lg text-display-lg text-error leading-none font-bold">{{ number_format($outOfStockCount) }}</p>
        </div>

        <!-- Card: Overstocked Items -->
        <div class="group relative bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 hover:shadow-lg hover:border-violet-500/40 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-violet-500/5 to-transparent rounded-bl-full"></div>
            <div class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center mb-3 group-hover:bg-violet-500/20 transition-colors">
                <span class="material-symbols-outlined text-violet-600 text-[22px]">widgets</span>
            </div>
            <p class="font-label-caps text-label-caps text-on-surface-variant mb-1">Overstocked Items</p>
            <p class="font-display-lg text-display-lg text-primary leading-none font-bold">{{ number_format($overstockedCount) }}</p>
        </div>
    </div>

    <!-- Bento Grid Layout -->
    <div class="grid grid-cols-12 gap-6">
        <!-- Filters & Controls (Left Column) -->
        <div class="col-span-12 lg:col-span-3 flex flex-col gap-6">
            <!-- Category Filter Card -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 shadow-sm">
                <div class="flex items-center gap-2.5 mb-4 pb-2 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-indigo-600">filter_alt</span>
                    <h3 class="font-headline-sm text-headline-sm text-primary">Category Filter</h3>
                </div>
                <form id="filter-form" action="{{ route('inventory') }}" method="GET" class="flex flex-col gap-2">
                    @if(request()->filled('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}"/>
                    @endif
                    <label class="flex items-center gap-2.5 font-body-md cursor-pointer hover:bg-surface-container-low p-2 rounded-xl transition-all duration-200">
                        <input type="checkbox" id="check-all" class="rounded border-outline-variant text-indigo-600 focus:ring-indigo-600/35 h-4 w-4 cursor-pointer" onchange="toggleAllCategories(this)" {{ empty($activeCategories) ? 'checked' : '' }}/>
                        <span class="font-medium text-primary text-sm">All Instruments</span>
                    </label>
                    <div class="space-y-0.5 mt-1 max-h-[300px] overflow-y-auto pr-1">
                        @foreach($categories as $cat)
                        <div class="group flex items-center justify-between gap-1 p-2 rounded-xl hover:bg-surface-container-low transition-all duration-200">
                            <label class="flex items-center gap-2.5 font-body-md cursor-pointer flex-1 overflow-hidden">
                                <input type="checkbox" name="categories[]" value="{{ $cat->name }}" class="category-checkbox rounded border-outline-variant text-indigo-600 focus:ring-indigo-600/35 h-4 w-4 cursor-pointer" {{ in_array($cat->name, $activeCategories) ? 'checked' : '' }}/>
                                <span class="truncate text-on-surface-variant group-hover:text-primary transition-colors text-sm">{{ $cat->name }}</span>
                            </label>
                            <div class="opacity-0 group-hover:opacity-100 flex items-center gap-1 transition-opacity shrink-0">
                                <button type="button" onclick="event.stopPropagation(); openEditCategoryModal({{ $cat->id }}, '{{ addslashes($cat->name) }}')" class="p-1 rounded-lg text-on-surface-variant hover:text-indigo-600 hover:bg-white transition-all shadow-sm" title="Rename Kategori">
                                    <span class="material-symbols-outlined text-[16px] block">edit</span>
                                </button>
                                <form action="{{ route('inventory.categories.destroy', $cat->id) }}" method="POST" class="inline" onsubmit="event.preventDefault(); showCustomConfirm(this, 'Apakah Anda yakin ingin menghapus kategori ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 rounded-lg text-error hover:bg-white transition-all shadow-sm" title="Hapus Kategori">
                                        <span class="material-symbols-outlined text-[16px] block">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </form>
                
                <!-- Add Category Form inside filter card -->
                <form action="{{ route('inventory.categories.store') }}" method="POST" class="mt-4 pt-4 border-t border-outline-variant flex gap-2">
                    @csrf
                    <input type="text" name="name" required placeholder="Tambah kategori..." class="flex-1 border border-outline-variant rounded-xl px-3 py-2 bg-surface-container-low text-on-surface text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
                    <button type="submit" class="bg-indigo-600 text-white p-2 rounded-xl text-xs font-bold hover:bg-indigo-700 transition-colors flex items-center justify-center shrink-0 shadow-sm" title="Tambah Kategori">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                    </button>
                </form>
            </div>

            <!-- Quick Actions Card -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-5 shadow-sm">
                <div class="flex items-center gap-2.5 mb-4 pb-2 border-b border-outline-variant">
                    <span class="material-symbols-outlined text-indigo-600">bolt</span>
                    <h3 class="font-headline-sm text-headline-sm text-primary">Quick Actions</h3>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <button class="border border-outline-variant rounded-xl p-3 flex flex-col items-center justify-center gap-2 hover:border-indigo-600 hover:text-indigo-600 hover:bg-indigo-50/50 transition-all duration-300 group">
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-indigo-600 transition-colors text-[24px]">inventory</span>
                        <span class="font-label-caps text-[10px] text-center text-on-surface-variant group-hover:text-indigo-600">Audit Stock</span>
                    </button>
                    <button class="border border-outline-variant rounded-xl p-3 flex flex-col items-center justify-center gap-2 hover:border-indigo-600 hover:text-indigo-600 hover:bg-indigo-50/50 transition-all duration-300 group">
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:text-indigo-600 transition-colors text-[24px]">local_shipping</span>
                        <span class="font-label-caps text-[10px] text-center text-on-surface-variant group-hover:text-indigo-600">Vendor Orders</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Data Table (Right Column) -->
        <div class="col-span-12 lg:col-span-9 bg-surface-container-lowest border border-outline-variant rounded-2xl overflow-hidden flex flex-col shadow-sm">
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-surface-container-low/40 border-b border-outline-variant">
                            <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold text-center w-16">QR</th>
                            <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold">Item Name</th>
                            <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold">Category</th>
                            <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold text-right">Current Stock</th>
                            <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold text-right whitespace-nowrap">Unit Price</th>
                            <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold">Status</th>
                            <th class="py-3.5 px-5 font-label-caps text-label-caps text-on-surface-variant font-bold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/60">
                        @foreach($items as $item)
                        <tr class="group/row hover:bg-surface-container-low/30 transition-all duration-200">
                            <!-- QR Column -->
                            <td class="py-3 px-5 text-center">
                                <button onclick="openQrModal({{ json_encode($item) }})" class="p-2 rounded-xl text-secondary hover:bg-secondary/10 transition-all duration-200 shadow-sm hover:shadow" title="View QR Code">
                                    <span class="material-symbols-outlined text-[20px] block">qr_code</span>
                                </button>
                            </td>
                            <!-- Item Name Column -->
                            <td class="py-3 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-xl overflow-hidden border border-outline-variant bg-surface-container-low flex items-center justify-center shrink-0 shadow-sm transition-all duration-300 group-hover/row:scale-105">
                                        @if($item->image_path && file_exists(public_path($item->image_path)))
                                            <img src="/{{ $item->image_path }}" alt="{{ $item->name }}" class="w-full h-full object-cover"/>
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-indigo-500/5 to-indigo-500/15 flex items-center justify-center">
                                                <span class="material-symbols-outlined text-indigo-600/40 text-[20px]">music_note</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span class="font-body-md font-semibold text-primary truncate">{{ $item->name }}</span>
                                        <span class="font-data-mono text-[10px] text-on-surface-variant/80 mt-0.5">{{ $item->item_id }}</span>
                                    </div>
                                </div>
                            </td>
                            <!-- Category Column -->
                            <td class="py-3 px-5 font-body-md text-on-surface-variant">{{ $item->category }}</td>
                            <!-- Current Stock Column -->
                            <td class="py-3 px-5 font-data-mono text-right">
                                <span class="{{ $item->stock == 0 ? 'text-error font-bold' : ($item->stock <= 5 ? 'text-error font-semibold' : 'text-primary') }}">
                                    {{ $item->stock }}
                                </span>
                            </td>
                            <!-- Unit Price Column -->
                            <td class="py-3 px-5 font-data-mono text-right whitespace-nowrap text-primary">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <!-- Status Column -->
                            <td class="py-3 px-5">
                                @if($item->status == 'Optimal')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-label-caps text-[10px] font-bold bg-green-500/10 text-green-700">Optimal</span>
                                @elseif($item->status == 'Low Stock')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-label-caps text-[10px] font-bold bg-amber-500/10 text-amber-700">Low Stock</span>
                                @elseif($item->status == 'Out of Stock')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-label-caps text-[10px] font-bold bg-error/10 text-error">Out of Stock</span>
                                @elseif($item->status == 'Overstocked')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg font-label-caps text-[10px] font-bold bg-violet-500/10 text-violet-700">Overstocked</span>
                                @endif
                            </td>
                            <!-- Actions Column -->
                            <td class="py-3 px-5 text-right">
                                <div class="flex justify-end gap-2 items-center">
                                    <button onclick="openEditModal({{ json_encode($item) }})" class="p-2 rounded-xl border border-outline-variant hover:border-indigo-600 hover:bg-indigo-50/50 text-on-surface-variant hover:text-indigo-600 transition-all duration-200 shadow-sm" title="Edit Item">
                                        <span class="material-symbols-outlined text-[18px] block">edit</span>
                                    </button>
                                    <form action="{{ route('inventory.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="event.preventDefault(); showCustomConfirm(this, 'Apakah Anda yakin ingin menghapus item ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl border border-outline-variant hover:border-error hover:bg-error/5 text-on-surface-variant hover:text-error transition-all duration-200 shadow-sm" title="Delete Item">
                                            <span class="material-symbols-outlined text-[18px] block">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        @if($items->isEmpty())
                        <tr>
                            <td colspan="7" class="py-12 text-center text-on-surface-variant font-body-md">
                                <div class="flex flex-col items-center justify-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-[40px] text-outline-variant mb-2">inbox</span>
                                    <p>Tidak ada barang inventaris ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <!-- Pagination Footer -->
            <div class="p-4 border-t border-outline-variant bg-surface-container-low/40 flex justify-between items-center text-on-surface-variant font-body-sm">
                <span>Showing {{ $items->count() }} of {{ $items->count() }} entries</span>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div id="add-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-md shadow-2xl animate-scale-up">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600">add_box</span>
                Add New Item
            </h3>
            <button onclick="closeAddModal()" class="p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined block">close</span>
            </button>
        </div>
        <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 font-body-sm">
            @csrf
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Item Name</label>
                <input type="text" name="name" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Category</label>
                <select name="category" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer transition-all">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Product Photo</label>
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
                    <label class="block font-semibold text-on-surface mb-1.5">Stock</label>
                    <input type="number" name="stock" min="0" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
                </div>
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5">Unit Price (Rp)</label>
                    <input type="number" step="0.01" name="price" min="0" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
                </div>
            </div>
            <div class="flex justify-end gap-2.5 pt-4 border-t border-outline-variant">
                <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors shadow-md">Save</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-md shadow-2xl animate-scale-up">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-outline-variant">
            <h3 class="font-headline-sm text-headline-sm text-primary flex items-center gap-2">
                <span class="material-symbols-outlined text-indigo-600">edit_note</span>
                Edit Item
            </h3>
            <button onclick="closeEditModal()" class="p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
                <span class="material-symbols-outlined block">close</span>
            </button>
        </div>
        <form id="edit-form" action="" method="POST" enctype="multipart/form-data" class="space-y-4 font-body-sm">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Item Name</label>
                <input type="text" id="edit-name" name="name" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Category</label>
                <select id="edit-category" name="category" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 cursor-pointer transition-all">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Product Photo</label>
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
                    <label class="block font-semibold text-on-surface mb-1.5">Stock</label>
                    <input type="number" id="edit-stock" name="stock" min="0" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
                </div>
                <div>
                    <label class="block font-semibold text-on-surface mb-1.5">Unit Price (Rp)</label>
                    <input type="number" step="0.01" id="edit-price" name="price" min="0" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
                </div>
            </div>
            <div class="flex justify-end gap-2.5 pt-4 border-t border-outline-variant">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors shadow-md">Save Changes</button>
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

<!-- QR Detail Modal -->
<div id="qr-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-sm shadow-2xl text-center font-body-sm relative animate-scale-up">
        <button onclick="closeQrModal()" class="absolute top-4 right-4 p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined block">close</span>
        </button>
        <div class="w-12 h-12 bg-secondary/10 text-secondary rounded-full flex items-center justify-center mx-auto mb-3">
            <span class="material-symbols-outlined text-[24px]">qr_code_2</span>
        </div>
        <h3 class="font-headline-sm text-headline-sm text-primary mb-1">QR Code Barang</h3>
        <p id="qr-item-name" class="font-body-md font-bold text-slate-700 mb-4"></p>
        
        <!-- QR Container (Print Area) -->
        <div id="qr-print-area" class="bg-white p-4 rounded-xl border border-slate-200 inline-block mb-6 shadow-sm">
            <img id="qr-image" src="" alt="QR Code" class="w-48 h-48 mx-auto object-contain"/>
            <div id="qr-print-meta" class="mt-2 text-center text-xs text-slate-800 font-bold hidden">
                <p id="qr-meta-id"></p>
                <p id="qr-meta-name"></p>
            </div>
        </div>
        
        <div class="flex justify-center gap-3">
            <button onclick="closeQrModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Tutup</button>
            <button onclick="printQrCode()" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors flex items-center gap-2 shadow-md">
                <span class="material-symbols-outlined text-[18px]">print</span> Cetak QR
            </button>
        </div>
    </div>
</div>

<!-- Live Scanner Modal -->
<div id="scan-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-md shadow-2xl font-body-sm relative animate-scale-up">
        <button onclick="closeScanModal()" class="absolute top-4 right-4 p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors z-10">
            <span class="material-symbols-outlined block">close</span>
        </button>
        <div class="flex items-center gap-2.5 mb-2">
            <span class="material-symbols-outlined text-indigo-600 text-[24px]">qr_code_scanner</span>
            <h3 class="font-headline-sm text-headline-sm text-primary">Scan QR Code Barang</h3>
        </div>
        <p class="text-on-surface-variant mb-4 text-xs">Posisikan QR Code barang di dalam area pemindaian kamera.</p>
        
        <!-- html5-qrcode reader element -->
        <div class="bg-black rounded-xl overflow-hidden relative border border-slate-300 shadow-inner">
            <div id="qr-reader" class="w-full"></div>
        </div>
        
        <div class="flex justify-end gap-3 mt-5 pt-4 border-t border-outline-variant">
            <button onclick="closeScanModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="edit-category-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-all">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-6 w-full max-w-sm shadow-2xl font-body-sm relative animate-scale-up">
        <button type="button" onclick="closeEditCategoryModal()" class="absolute top-4 right-4 p-1 rounded-full hover:bg-surface-container-high text-on-surface-variant hover:text-primary transition-colors">
            <span class="material-symbols-outlined block">close</span>
        </button>
        <div class="w-12 h-12 bg-secondary/10 text-secondary rounded-full flex items-center justify-center mx-auto mb-3">
            <span class="material-symbols-outlined text-[24px]">edit_note</span>
        </div>
        <h3 class="font-headline-sm text-headline-sm text-primary text-center mb-4">Rename Kategori</h3>
        <form id="edit-category-form" action="" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block font-semibold text-on-surface mb-1.5">Nama Kategori</label>
                <input type="text" id="edit-category-name" name="name" required class="w-full border border-outline-variant rounded-xl px-3.5 py-2.5 bg-surface-container-low text-on-surface focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-all"/>
            </div>
            <div class="flex justify-end gap-2.5 pt-4 border-t border-outline-variant">
                <button type="button" onclick="closeEditCategoryModal()" class="px-5 py-2.5 border border-outline-variant text-on-surface-variant hover:text-primary rounded-full font-semibold hover:bg-surface-container-low transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition-colors shadow-md">Simpan</button>
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
