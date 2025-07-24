<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\RentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BillController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('tenants', TenantController::class);
Route::resource('rents', RentController::class);
Route::get('/rents/{rent}/invoice', [RentController::class, 'invoice'])->name('rents.invoice');
Route::post('/rents/{rent}/comments', [CommentController::class, 'store'])->name('comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings/clean', [SettingsController::class, 'clean'])->name('settings.clean');
Route::get('/settings/download', [SettingsController::class, 'download'])->name('settings.download');

Route::get('bills', [BillController::class, 'index'])->name('bills.index');
Route::post('bills/types', [BillController::class, 'storeType'])->name('bills.storeType');
Route::post('bills', [BillController::class, 'store'])->name('bills.store');
Route::get('bills/{bill}/edit', [BillController::class, 'edit'])->name('bills.edit');
Route::put('bills/{bill}', [BillController::class, 'update'])->name('bills.update');
Route::delete('bills/{bill}', [BillController::class, 'destroy'])->name('bills.destroy');

Route::get('bill-types/{bill_type}/edit', [BillController::class, 'editType'])->name('bill-types.edit');
Route::put('bill-types/{bill_type}', [BillController::class, 'updateType'])->name('bill-types.update');
Route::delete('bill-types/{bill_type}', [BillController::class, 'destroyType'])->name('bill-types.destroy');
