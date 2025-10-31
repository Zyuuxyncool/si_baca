<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class CeritaController extends Controller
{
    public function index()
    {
        return view('User.cerita.index');
    }
}