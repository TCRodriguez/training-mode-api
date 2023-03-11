<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\GameNotation;
use Illuminate\Http\Request;

class GameNotationController extends Controller
{
    public function index(Request $request, $gameId)
    {
        $gameNotations = GameNotation::where('game_id', $gameId)->with('directionalInputs')->get();

        return $gameNotations;
    }
}
