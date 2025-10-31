<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class LogoController extends Controller
{
    public function index()
    {
        return view('user.logo.index');
    }
}