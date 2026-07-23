<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomepageController as AdminHomepageController;
use App\Http\Controllers\Admin\HomepageExperienceController;
use App\Http\Controllers\Admin\HomepageExpertiseCardController;
use App\Http\Controllers\Admin\HomepageProjectController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PrivacyController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class);
Route::get('/privacy', PrivacyController::class)->name('privacy');
Route::get('/robots.txt', [SeoController::class, 'robots']);
Route::get('/sitemap.xml', [SeoController::class, 'sitemap']);

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::prefix('homepage')->name('homepage.')->group(function (): void {
        Route::get('/', [AdminHomepageController::class, 'index'])->name('index');
        Route::post('/', [AdminHomepageController::class, 'store'])->name('store');
        Route::get('/images', [ImageController::class, 'picker'])->name('images');
        Route::get('/{homepage}/preview', [AdminHomepageController::class, 'preview'])->name('preview');
        Route::get('/{homepage}/edit', [AdminHomepageController::class, 'edit'])->name('edit');
        Route::put('/{homepage}', [AdminHomepageController::class, 'update'])->name('update');
        Route::post('/{homepage}/activate', [AdminHomepageController::class, 'activate'])->name('activate');
        Route::post('/{homepage}/duplicate', [AdminHomepageController::class, 'duplicate'])->name('duplicate');
        Route::delete('/{homepage}', [AdminHomepageController::class, 'destroy'])->name('destroy');
    });

    Route::resource('projects', HomepageProjectController::class)->except(['show', 'destroy']);
    Route::resource('experiences', HomepageExperienceController::class)->except(['show', 'destroy']);
    Route::resource('expertise', HomepageExpertiseCardController::class)->parameters([
        'expertise' => 'expertise',
    ])->except(['show', 'destroy']);

    Route::prefix('images')->name('images.')->group(function (): void {
        Route::get('/', [ImageController::class, 'index'])->name('index');
        Route::get('/create', [ImageController::class, 'create'])->name('create');
        Route::post('/', [ImageController::class, 'store'])->name('store');
        Route::get('/{image}/edit', [ImageController::class, 'edit'])->name('edit');
        Route::put('/{image}', [ImageController::class, 'update'])->name('update');
        Route::delete('/{image}', [ImageController::class, 'destroy'])->name('destroy');
    });
});

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
