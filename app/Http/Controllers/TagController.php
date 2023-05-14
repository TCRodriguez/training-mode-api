<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request, $gameId)
    {
        $tags = Tag::where('game_id', $gameId)->get();

        return $tags;
    }
}
