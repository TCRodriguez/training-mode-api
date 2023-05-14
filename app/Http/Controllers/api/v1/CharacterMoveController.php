<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\CharacterMove;
use App\Models\Tag;
use App\Utilities\Tagger;
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
            'notations',
            'zones',
            'tags'
        ])->where('character_id', $characterId)->get();

        return $characterMoves;
    }

    public function addCharacterMoveTag(Request $request, $gameId, $characterId, $characterMoveId)
    {
        // return $request;
        $characterMoveTags = $request->tags;

        $characterMove = CharacterMove::where('id', $characterMoveId)->firstOrFail();

        Tagger::tagCharacterMove($gameId, $characterMove, $characterMoveTags);

        $characterMove = CharacterMove::where('id', $characterMoveId)
                        ->with('tags')
                        ->firstOrFail();

        return $characterMove;

    }
    
    public function removeCharacterMoveTag(Request $request, $gameId, $characterId, $characterMoveId, $tagId)
    {
        // return $request;
        // $characterMoveTags = $request->tags;

        // $characterMove = CharacterMove::where('id', $characterMoveId)->firstOrFail();

        // Tagger::untagCharacterMove($gameId, $characterMove, $characterMoveTags);

        // $characterMove = CharacterMove::where('id', $characterMoveId)
        //                 ->with('tags')
        //                 ->firstOrFail();

        // return $characterMove;
        $tag = Tag::where('id', $tagId)->firstOrFail();

        // dd($tag);
        $characterMove = CharacterMove::where('id', $characterMoveId)->firstOrFail();
        Tagger::untagCharacterMove($gameId, $characterMove, array($tag->name));

        $characterMove = CharacterMove::where('id', $characterMoveId)
            ->with('tags')
            ->firstOrFail();

        return $characterMove;

    }
}
