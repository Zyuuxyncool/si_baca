<?php

use Illuminate\Support\Facades\Route;

// Landing page publik
Route::get('/', [App\Http\Controllers\User\LandingPageController::class, 'index'])->name('landing');

// Redirect /buyer ke landing
Route::redirect('/user', '/buyer/landing')->name('buyer');

// Route /buyer/landing publik
Route::get('/landing', [App\Http\Controllers\User\LandingPageController::class, 'index'])->name('user.landing.index');
Route::get('/kontak', [App\Http\Controllers\User\KontakController::class, 'index'])->name('user.kontak.index');
Route::get('/logo', [App\Http\Controllers\User\LogoController::class, 'index'])->name('user.logo.index');
Route::get('/si-baca', [App\Http\Controllers\User\SiBacaController::class, 'index'])->name('user.si_baca.index');
Route::get('/cerita', [App\Http\Controllers\User\CeritaController::class, 'index'])->name('user.cerita.index');

// Group route buyer yang butuh login
Route::prefix('/user')->middleware(['auth'])->name('buyer.')->group(function () {
    
});
