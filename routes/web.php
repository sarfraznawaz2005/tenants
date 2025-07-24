<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\RentController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CommentController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('tenants', TenantController::class);
Route::resource('rents', RentController::class);
Route::post('/rents/{rent}/comments', [CommentController::class, 'store'])->name('comments.store');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
Route::post('/settings/clean', [SettingsController::class, 'clean'])->name('settings.clean');
Route::get('/settings/download', [SettingsController::class, 'download'])->name('settings.download');
