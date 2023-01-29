<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Character;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    public function index(Request $request, $gameId)
    {
        $characters = Character::where('game_id', $gameId)->get();

        

        return $characters;
    }
}
