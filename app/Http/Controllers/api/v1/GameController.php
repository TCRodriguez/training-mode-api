<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;


class GameController extends Controller
{
    public function index(Request $request)
    {
        $games = Game::all();

        return $games;
    }

    public function show(Request $request, $gameId)
    {
        $game = Game::where('id', $gameId)->firstOrFail();

        return $game;
    }
}
