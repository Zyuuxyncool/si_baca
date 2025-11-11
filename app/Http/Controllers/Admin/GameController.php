<?php

namespace Appp\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GameService;

class GameController extends Controller
{
    protected $gameService;

    public function __construct()
    {
        $this->gameService = new GameService();
    }

    public function index()
    {
        return view('admin.games.index');
    }

    public function search(Request $request)
    {
        $games = $this->gameService->search($request->all());
        return view('admin.games._table', ['games' => $games]);
    }
}