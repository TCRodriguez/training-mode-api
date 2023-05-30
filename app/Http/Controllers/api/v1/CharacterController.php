<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\Note;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    public function index(Request $request, $gameId)
    {
        $characters = Character::where('game_id', $gameId)
            ->with('notations')
            ->with('notes')
            ->get();

        return $characters;
    }
    
    public function show(Request $request, $gameId, $characterId)
    {
        $character = Character::where('game_id', $gameId)
            ->where('id', $characterId)
            ->with('notations')
            ->with('notes')
            ->get();

        return $character;
    }

    public function addNote(Request $request, $gameId, $characterId)
    {
        // return $request->title;
        // return $characterId;
        // $note = new Note(['body' => $request->body]);

        // $character = Character::where('id', $characterId)->firstOrFail();
        $character = Character::find($characterId);
        // dd($character);
        // return $character;

        $note = $character->notes()->create([
            // 'title' => $request->title,
            'title' => isset($request->title) ? $request->title : 'Untitled Note',
            'body' => $request->body,
            'user_id' => 1
        ]);

        $character = Character::with('notes')->find($characterId);

        // $character->notes()->save($note);
        // $
        return $character;
    }
}
