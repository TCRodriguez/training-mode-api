<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CharacterController extends Controller
{
    public function index(Request $request, $gameId)
    {
        $characters = Character::where('game_id', $gameId)
            ->with('notations')
            ->with(['notes.tags' => function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            }])->get();

        return $characters;
    }

    public function characterIndexGuest(Request $request, $gameId)
    {
        $characters = Character::where('game_id', $gameId)
            ->with('notations')
            ->get();

        return $characters;
    }
    
    public function show(Request $request, $gameId, $characterId)
    {
        $character = Character::where('game_id', $gameId)
            ->where('id', $characterId)
            ->with('notations')
            ->with(['notes' => function ($query) {
                $query->where('user_id', Auth::id());
            }])->get();

        return $character;
    }

    public function addNote(Request $request, $gameId, $characterId)
    {
        $character = Character::find($characterId);

        $note = $character->notes()->create([
            'title' => isset($request->title) ? $request->title : 'Untitled Note',
            'body' => $request->body,
            'user_id' => Auth::id(),
            'game_id' => $gameId
        ]);

        $character = Character::with('notes')->find($characterId);

        return $character;
    }
}
