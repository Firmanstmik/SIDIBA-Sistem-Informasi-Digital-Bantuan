<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\BantuanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MonevController;

// Auth routes - HARUS di atas public routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public routes - setelah auth routes
Route::get('/', [BeneficiaryController::class, 'publicIndex'])->name('home');
Route::get('/public/map', [BeneficiaryController::class, 'publicMap'])->name('public.map');
Route::get('/api/beneficiaries', [BeneficiaryController::class, 'getBeneficiariesJson']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Beneficiaries routes
    Route::get('/beneficiaries', [BeneficiaryController::class, 'index'])->name('beneficiaries.index');
    Route::get('/beneficiaries/create', [BeneficiaryController::class, 'create'])->name('beneficiaries.create');
    Route::get('/beneficiaries/check-nik', [BeneficiaryController::class, 'checkNik'])->name('beneficiaries.checkNik');
    Route::post('/beneficiaries', [BeneficiaryController::class, 'store'])->name('beneficiaries.store');
    Route::get('/beneficiaries/{beneficiary}/edit', [BeneficiaryController::class, 'edit'])->name('beneficiaries.edit');
    Route::put('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'update'])->name('beneficiaries.update');
    Route::delete('/beneficiaries/{beneficiary}', [BeneficiaryController::class, 'destroy'])->name('beneficiaries.destroy');
    
    // Import/Export routes
    Route::get('/beneficiaries/import', [BeneficiaryController::class, 'showImport'])->name('beneficiaries.import');
    Route::post('/beneficiaries/import', [BeneficiaryController::class, 'import'])->name('beneficiaries.import.post');
    Route::get('/beneficiaries/export', [BeneficiaryController::class, 'export'])->name('beneficiaries.export');
    Route::get('/beneficiaries/export-csv', [BeneficiaryController::class, 'exportManual'])->name('beneficiaries.export.csv');
    Route::get('/beneficiaries/template', [BeneficiaryController::class, 'downloadTemplate'])->name('beneficiaries.downloadTemplate');
    
    // Beneficiaries Export Excel routes
    Route::get('/beneficiaries/export/excel-html', [BeneficiaryController::class, 'exportExcelHtml'])
        ->name('beneficiaries.export.excel-html');
    Route::get('/beneficiaries/export/excel', [BeneficiaryController::class, 'exportExcel'])
        ->name('beneficiaries.export.excel');
    
    // Monev routes
    Route::get('/monev', [MonevController::class, 'index'])->name('monev.index');
    Route::get('/monev/create/{beneficiary}', [MonevController::class, 'create'])->name('monev.create');
    Route::post('/monev/{beneficiary}', [MonevController::class, 'store'])->name('monev.store');
    Route::get('/monev/history/{beneficiary}', [MonevController::class, 'history'])->name('monev.history');
    Route::get('/monev/report', [MonevController::class, 'report'])->name('monev.report');
    Route::get('/monev/document/{monev}', [MonevController::class, 'viewDocument'])->name('monev.document');
    
    // Monev Export routes
    Route::get('/monev/{id}/download-report', [MonevController::class, 'downloadReport'])
        ->name('monev.download-report');
    Route::get('/monev/{id}/export-excel', [MonevController::class, 'exportExcelIndividual'])
        ->name('monev.export-excel-individual');
    
    // Admin only routes
    Route::middleware(['admin'])->group(function () {
        Route::resource('bantuan', BantuanController::class);
        Route::resource('users', UserController::class);
    });
});

// Route untuk testing
Route::get('/test', function () {
    return 'Laravel berhasil! Routes sudah diperbaiki';
});