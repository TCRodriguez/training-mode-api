<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CharacterMoveController extends Controller
{
    public function index(Request $request, $gameId, $characterId)
    {
        return 'Move index';
    }
}
