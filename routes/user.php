<?php

use Illuminate\Support\Facades\Route;

// Landing page publik
Route::get('/', [App\Http\Controllers\User\LandingPageController::class, 'index'])->name('landing');

// Redirect /buyer ke landing
Route::redirect('/user', '/landing')->name('buyer');

// Route /buyer/landing publik
Route::get('/landing', [App\Http\Controllers\User\LandingPageController::class, 'index'])->name('user.landing.index');
Route::get('/logo', [App\Http\Controllers\User\LogoController::class, 'index'])->name('user.logo.index');
Route::get('/si-baca', [App\Http\Controllers\User\SiBacaController::class, 'index'])->name('user.si_baca.index');
Route::get('/cerita', [App\Http\Controllers\User\CeritaController::class, 'index'])->name('user.cerita.index');
// Stream video for cerita (supports HTTP Range requests)
Route::get('/cerita/{nama}/video', [App\Http\Controllers\User\CeritaController::class, 'streamVideo'])->name('user.cerita.stream');

Route::get('/cerita/{nama}', [App\Http\Controllers\User\CeritaController::class, 'show'])->name('user.cerita.show');
Route::get('/cerita/{nama}/status', [App\Http\Controllers\User\CeritaController::class, 'status'])->name('user.cerita.status');
// Game routes - require authentication
Route::middleware('auth')->group(function () {
	Route::get('/cerita/{nama}/menu_cari_kata', [App\Http\Controllers\User\GameController::class, 'menuCariKata'])->name('user.games.menu_cari_kata.index');
	Route::get('/cerita/{nama}/menu_ruang_teka', [App\Http\Controllers\User\GameController::class, 'menuRuangTeka'])->name('user.games.menu_ruang_teka.index');
	Route::get('/cerita/{nama}/menu_cari_kata/cari_kata', [App\Http\Controllers\User\GameController::class, 'cariKata'])->name('user.cerita.menu_cari_kata.cari_kata');
	Route::get('/cerita/{nama}/menu_ruang_teka/ruang_teka', [App\Http\Controllers\User\GameController::class, 'ruangTeka'])->name('user.cerita.menu_ruang_teka.ruang_teka');

	// Finish and store score for ruang teka
	// The route is intended to be POSTed (AJAX) to save score. Some users may accidentally visit the URL via GET
	// (for example by typing it in the browser). Provide a friendly redirect for GET requests instead of a 405.
	Route::get('/cerita/{nama}/ruang_teka/finish', function ($nama) {
		return redirect()->route('user.games.menu_ruang_teka.index', ['nama' => $nama]);
	})->name('user.cerita.ruang_teka.finish.get');

	Route::post('/cerita/{nama}/ruang_teka/finish', [App\Http\Controllers\User\GameController::class, 'finish'])->name('user.cerita.ruang_teka.finish');
});

Route::get('/kontak', [App\Http\Controllers\User\KontakController::class, 'index'])->name('kontak');
Route::resource('/kontak', App\Http\Controllers\User\KontakController::class)->only(['index', 'create', 'store'])->names(['index' => 'user.kontak.index','create' => 'user.kontak.create','store' => 'user.kontak.store',]);
