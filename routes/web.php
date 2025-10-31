<?php

use Illuminate\Support\Facades\Route;

Route::middleware([])->group(__DIR__ . '/auth.php');
Route::middleware([])->group(__DIR__ . '/user.php');
Route::middleware(['auth', 'io'])->group(__DIR__ . '/admin.php');
