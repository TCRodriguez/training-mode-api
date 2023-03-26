<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\CharacterMove;
use Illuminate\Http\Request;

class CharacterMoveController extends Controller
{
    public function index(Request $request, $gameId, $characterId)
    {
        // return 'Move index';
        // $characterMoves = CharacterMove::where('character_id', $characterId)
        // ->with('directionalInputs')
        // ->with('notations')
        // ->with('attackButtons')
        // ->get();
        $characterMoves = CharacterMove::with([
            'directionalInputs',
            'attackButtons',
            'notations'
        ])->where('character_id', $characterId)->get();

        return $characterMoves;
    }
}
