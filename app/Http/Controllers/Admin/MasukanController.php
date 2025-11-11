<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\KontakService;
use Illuminate\Http\Request;

class MasukanController extends Controller
{
    protected $kontakService;

    public function __construct()
    {
        $this->kontakService = new KontakService();
    }

    public function index()
    {
        return view('admin.masukan.index');
    }

    public function search(Request $request)
    {
        $masukans = $this->kontakService->search($request->all());
        return view('admin.masukan._table', compact('masukans'));
    }
}