<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\CharacterMove;
use App\Models\Tag;
use App\Utilities\Tagger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CharacterMoveController extends Controller
{
    public function index(Request $request, $gameId, $characterId)
    {
        $characterMoves = CharacterMove::with([
            'directionalInputs',
            'attackButtons',
            'notations',
            'followUps',
            'followsUp',
        ])
            ->with(['tags' => function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            }])
            ->where('character_id', $characterId)
            ->get();

        return $characterMoves;
    }

    public function guestCharacterMoveIndex(Request $request, $gameId, $characterId)
    {
        $characterMoves = CharacterMove::with([
            'directionalInputs',
            'attackButtons',
            'notations',
            'followUps',
            'followsUp',
        ])
            ->where('character_id', $characterId)
            ->get();

        return $characterMoves;
    }

    public function addCharacterMoveTag(Request $request, $gameId, $characterId, $characterMoveId)
    {
        $characterMoveTags = $request->tags;

        $characterMove = CharacterMove::where('id', $characterMoveId)->firstOrFail();

        Tagger::tagCharacterMove($gameId, $characterMove, $characterMoveTags);

        $characterMove = CharacterMove::where('id', $characterMoveId)
                        ->with(['tags' => function ($query) {
                            $query->where('user_id', Auth::id());
                        }])
                        ->firstOrFail();

        return $characterMove;
    }
    
    public function removeCharacterMoveTag(Request $request, $gameId, $characterId, $characterMoveId, $tagId)
    {
        $tag = Tag::where('id', $tagId)->firstOrFail();

        $characterMove = CharacterMove::where('id', $characterMoveId)->firstOrFail();
        Tagger::untagCharacterMove($gameId, $characterMove, array($tag->name));

        $characterMove = CharacterMove::where('id', $characterMoveId)
            ->with('tags')
            ->firstOrFail();

        return $characterMove;
    }
}
