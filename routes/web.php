<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/inventory', [\App\Http\Controllers\InventoryController::class, 'index'])->name('inventory');
    Route::post('/inventory', [\App\Http\Controllers\InventoryController::class, 'store'])->name('inventory.store');
    Route::put('/inventory/{id}', [\App\Http\Controllers\InventoryController::class, 'update'])->name('inventory.update');
    Route::delete('/inventory/{id}', [\App\Http\Controllers\InventoryController::class, 'destroy'])->name('inventory.destroy');
    Route::get('/inventory/export', [\App\Http\Controllers\InventoryController::class, 'export'])->name('inventory.export');
    Route::post('/inventory/categories', [\App\Http\Controllers\InventoryController::class, 'storeCategory'])->name('inventory.categories.store');
    Route::put('/inventory/categories/{id}', [\App\Http\Controllers\InventoryController::class, 'updateCategory'])->name('inventory.categories.update');
    Route::delete('/inventory/categories/{id}', [\App\Http\Controllers\InventoryController::class, 'destroyCategory'])->name('inventory.categories.destroy');
    Route::get('/finance', [\App\Http\Controllers\FinanceController::class, 'index'])->name('finance');
    Route::post('/finance', [\App\Http\Controllers\FinanceController::class, 'store'])->name('finance.store');
    Route::get('/finance/export', [\App\Http\Controllers\FinanceController::class, 'export'])->name('finance.export');
    Route::post('/finance/categories', [\App\Http\Controllers\FinanceController::class, 'storeCategory'])->name('finance.categories.store');
    Route::put('/finance/categories/{id}', [\App\Http\Controllers\FinanceController::class, 'updateCategory'])->name('finance.categories.update');
    Route::delete('/finance/categories/{id}', [\App\Http\Controllers\FinanceController::class, 'destroyCategory'])->name('finance.categories.destroy');
    Route::get('/vendors', [\App\Http\Controllers\VendorController::class, 'index'])->name('vendors');
    Route::post('/vendors', [\App\Http\Controllers\VendorController::class, 'store'])->name('vendors.store');
    Route::put('/vendors/{id}', [\App\Http\Controllers\VendorController::class, 'update'])->name('vendors.update');
    Route::delete('/vendors/{id}', [\App\Http\Controllers\VendorController::class, 'destroy'])->name('vendors.destroy');
    Route::get('/vendors/export', [\App\Http\Controllers\VendorController::class, 'export'])->name('vendors.export');
    Route::get('/hr', [\App\Http\Controllers\HRController::class, 'index'])->name('hr');
    Route::post('/hr', [\App\Http\Controllers\HRController::class, 'store'])->name('hr.store');
    Route::put('/hr/{id}', [\App\Http\Controllers\HRController::class, 'update'])->name('hr.update');
    Route::delete('/hr/{id}', [\App\Http\Controllers\HRController::class, 'destroy'])->name('hr.destroy');
    Route::post('/hr/payroll', [\App\Http\Controllers\HRController::class, 'processPayroll'])->name('hr.payroll');
    Route::post('/hr/leaves/{id}/approve', [\App\Http\Controllers\HRController::class, 'approveLeave'])->name('hr.leaves.approve');
    Route::post('/hr/leaves/{id}/reject', [\App\Http\Controllers\HRController::class, 'rejectLeave'])->name('hr.leaves.reject');
    Route::post('/hr/documents', [\App\Http\Controllers\HRController::class, 'uploadDocument'])->name('hr.documents.store');
    Route::delete('/hr/documents/{id}', [\App\Http\Controllers\HRController::class, 'deleteDocument'])->name('hr.documents.destroy');
    Route::get('/drive', function () { return view('drive'); })->name('drive');
});

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});
