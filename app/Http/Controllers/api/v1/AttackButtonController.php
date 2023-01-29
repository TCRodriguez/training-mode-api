<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\AttackButton;
use Illuminate\Http\Request;

class AttackButtonController extends Controller
{
    public function index(Request $request, $gameId)
    {
        $attackButtons = AttackButton::where('game_id', $gameId)->get();

        return $attackButtons;
    }
}
