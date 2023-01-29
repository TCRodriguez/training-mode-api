<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\CharacterCombo;
use Illuminate\Http\Request;

class CharacterComboController extends Controller
{
    public function index(Request $request, $gameId, $characterId)
    {
        // $characterCombos = CharacterCombo::where('characterId', $characterId)
        //     ->where('user_id', $request->user()->id)
        //     ->get();
        
        return 'Get Character Combos HIT';
    }

    public function show(Request $request, $gameId, $characterId, $characterCombo)
    {
        return 'GET Character Combo HIT';
    }

    public function store(Request $request, $gameId, $characterId)
    {
        return 'POST Character Combo HIT';
    }


    public function update(Request $request, $gameId, $characterId)
    {
        return 'PUT Character Combo HIT';
    }


    public function delete(Request $request, $gameId, $characterId)
    {
        return 'DELETE Character Combo HIT';
    }
}
