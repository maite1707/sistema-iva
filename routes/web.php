<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanySelectionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PurchaseBookController;
use App\Http\Controllers\SaleBookController;
use App\Http\Controllers\TaxAnnexController;
use App\Http\Controllers\IvaMovementController;
use App\Http\Controllers\ThirdPartySaleController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])
        ->middleware('company')
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------
    | Empresas
    |--------------------------------------------------------------------
    */
    Route::resource('companies', CompanyController::class);

    /*
    |--------------------------------------------------------------------
    | Selección de empresa
    |--------------------------------------------------------------------
    */
    Route::get('/select-company', [CompanySelectionController::class, 'index'])
        ->name('companies.select');

    Route::post('/select-company', [CompanySelectionController::class, 'store'])
        ->name('companies.select.store');

    Route::post('/change-company', [CompanySelectionController::class, 'store'])
        ->name('companies.change');

    Route::post('/leave-company', [CompanySelectionController::class, 'destroy'])
        ->name('companies.leave');

    /*
    |--------------------------------------------------------------------
    | Catálogos de la empresa activa (requieren empresa seleccionada)
    |--------------------------------------------------------------------
    */
 Route::middleware('company')->group(function () {
    Route::resource('customers', CustomerController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::get('/purchases-book', [PurchaseBookController::class, 'index'])->name('purchases.book');
    Route::resource('sales', SaleController::class);
    Route::get('/sales-book', [SaleBookController::class, 'index'])->name('sales.book');

    Route::get('/tax-annexes', [TaxAnnexController::class, 'index'])->name('tax-annexes.index');
    Route::post('/tax-annexes/generate', [TaxAnnexController::class, 'generate'])->name('tax-annexes.generate');
    Route::get('/tax-annexes/{generation}/download', [TaxAnnexController::class, 'download'])->name('tax-annexes.download');
    Route::resource('iva-movements', IvaMovementController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('third-party-sales', ThirdPartySaleController::class)->only(['index', 'create', 'store', 'destroy']);
});

});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';