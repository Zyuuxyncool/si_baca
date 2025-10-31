<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class KontakController extends Controller
{
    public function index()
    {
        return view('user.kontak.index');
    }
}