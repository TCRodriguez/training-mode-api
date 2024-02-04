<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\AttackButton;
use Illuminate\Http\Request;

class AttackButtonController extends Controller
{
    public function index(Request $request, $gameId)
    {
        $attackButtons = AttackButton::where('game_id', $gameId)->with('notations')->get();

        return $attackButtons;
    }

    public function indexWithDeviceMappings(Request $request, $gameId, $deviceId)
    {
        // TODO: Specify device to return basedon $deviceId
        $attackButtons = AttackButton::where('game_id', $gameId)->with('notations')->with('deviceButtons')->get();

        return $attackButtons;
    }
}
