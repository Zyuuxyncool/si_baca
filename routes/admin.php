<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/admin', 'admin/dashboard')->name('admin');
Route::prefix('/admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('/user', App\Http\Controllers\Admin\UserController::class)->except(['show']);
    Route::post('/user/search', [App\Http\Controllers\Admin\UserController::class, 'search'])->name('user.search');

    Route::resource('/masukan', App\Http\Controllers\Admin\MasukanController::class)->only(['index', 'destroy']);
    Route::post('/masukan/search', [App\Http\Controllers\Admin\MasukanController::class, 'search'])->name('masukan.search');

    Route::resource('/cerita', App\Http\Controllers\Admin\CeritaController::class)->except(['show']);
    Route::post('/cerita/search', [App\Http\Controllers\Admin\CeritaController::class, 'search'])->name('cerita.search');
    // Status endpoint for video processing state (used by preview-video component)
    Route::get('/cerita/{id}/status', [App\Http\Controllers\Admin\CeritaController::class, 'status'])->name('cerita.status');

    // Ruang Teka templates (admin)
    Route::resource('/ruang_teka', App\Http\Controllers\Admin\RuangTekaTemplateController::class)->except(['show']);
    Route::post('/ruang_teka/search', [App\Http\Controllers\Admin\RuangTekaTemplateController::class, 'search'])->name('ruang_teka.search');
    // AJAX endpoint to generate grid preview
    Route::post('/ruang_teka/generate', [App\Http\Controllers\Admin\RuangTekaTemplateController::class, 'generate'])->name('ruang_teka.generate');
    // AJAX endpoint to generate grid preview and optionally save into the template
    Route::post('/ruang_teka/generate-save', [App\Http\Controllers\Admin\RuangTekaTemplateController::class, 'generateAndSave'])->name('ruang_teka.generate_save');

    // Cari Kata templates (admin)
    Route::resource('/cari_kata', App\Http\Controllers\Admin\CariKataTemplateController::class)->except(['show']);
    Route::post('/cari_kata/search', [App\Http\Controllers\Admin\CariKataTemplateController::class, 'search'])->name('cari_kata.search');
    // AJAX endpoint to generate grid preview
    Route::post('/cari_kata/generate', [App\Http\Controllers\Admin\CariKataTemplateController::class, 'generate'])->name('cari_kata.generate');
    // AJAX endpoint to generate grid preview and save into the template
    Route::post('/cari_kata/generate-save', [App\Http\Controllers\Admin\CariKataTemplateController::class, 'generateAndSave'])->name('cari_kata.generate_save');
});
