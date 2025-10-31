<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/admin', 'admin/dashboard')->name('admin');
Route::prefix('/admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

 
});
