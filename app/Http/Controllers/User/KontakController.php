<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\KontakService;
use Illuminate\Http\Request;

class KontakController extends Controller
{
    protected $kontakService;
    public function __construct()
    {
        $this->kontakService = new KontakService();
    }
    public function index()
    {
        return view('user.kontak.index');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            // Form uses 'pesan'; map it to 'masukan'
            'pesan' => ['required', 'string'],
        ]);

        $this->kontakService->store([
            'user_id' => $user?->id,
            'nama' => $validated['nama'],
            'masukan' => $validated['pesan'],
        ]);

        return response()->json(['message' => 'Masukan berhasil dikirim']);
    }

    public function create()
    {
        return view('user.kontak._form');
    }
}
