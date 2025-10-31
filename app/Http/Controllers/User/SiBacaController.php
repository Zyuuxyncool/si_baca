<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiBacaController extends Controller
{
    public function index()
    {
        return view('User.si_baca.index');
    }
}