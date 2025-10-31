<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login_proses'])->name('login.proses');
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::get('/register', [App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register_proses'])->name('register.proses');

Route::get('/forgot_password', [App\Http\Controllers\AuthController::class, 'forgot_password'])->name('forgot_password');
Route::post('/forgot_password', [App\Http\Controllers\AuthController::class, 'forgot_password_proses'])->name('forgot_password.proses');
Route::get('/reset_password/{token}', [App\Http\Controllers\AuthController::class, 'reset_password'])->name('reset_password');
Route::post('/reset_password/{token}', [App\Http\Controllers\AuthController::class, 'reset_password_proses'])->name('reset_password.proses');
