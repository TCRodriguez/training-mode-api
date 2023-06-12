<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index(Request $request, $gameId)
    {
        $tags = Tag::where('game_id', $gameId)
            ->where('user_id', Auth::id())
            ->with('characterMoves')
            ->get();

        return $tags;
    }

    public function characterMoveTagIndex(Request $request, $gameId, $characterId)
    {

        // $notes = Note::where('game_id', $gameId)
        // ->where('notable_type', 'App\Models\Game')
        // ->where('user_id', Auth::id())
        // ->get();
        $characterMoveTags = Tag::where('game_id', $gameId)
            ->where('taggable_type', 'App\Models\CharacterMove')
            ->where('user_id', Auth::id())
            ->get();

        return $characterMoveTags;
    }
}
