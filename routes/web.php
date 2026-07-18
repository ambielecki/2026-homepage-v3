<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
});

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::prefix('images')->name('images.')->group(function (): void {
        Route::get('/', [ImageController::class, 'index'])->name('index');
        Route::get('/create', [ImageController::class, 'create'])->name('create');
        Route::post('/', [ImageController::class, 'store'])->name('store');
        Route::get('/{image}/edit', [ImageController::class, 'edit'])->name('edit');
        Route::put('/{image}', [ImageController::class, 'update'])->name('update');
    });
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
