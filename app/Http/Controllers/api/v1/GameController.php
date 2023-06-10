<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $games = Game::with('notes')->get();

        return $games;
    }

    public function show(Request $request, $gameId)
    {
        $game = Game::where('id', $gameId)->with(['notes' => function ($query) {
            $query->where('user_id', Auth::id());
        }])->firstOrFail();

        return $game;
    }

    public function addNote(Request $request, $gameId)
    {
        // return $request;
        // return $characterId;
        // $note = new Note(['body' => $request->body]);

        // $character = Character::where('id', $characterId)->firstOrFail();
        // $character = Character::find($characterId);
        $game = Game::find($gameId);
        // dd($character);
        // return $character;

        $note = $game->notes()->create([
            // 'title' => $request->title,
            'title' => isset($request->title) ? $request->title : 'Untitled Note',
            'body' => $request->body,
            'user_id' => Auth::id(),
            'game_id' => $gameId
        ]);

        // $character = Character::with('notes')->find($characterId);
        $game = Game::with('notes')->find($gameId);

        // $character->notes()->save($note);
        // $
        return $game;
    }
}
