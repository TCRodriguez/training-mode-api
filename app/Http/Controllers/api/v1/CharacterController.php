<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\CharacterMove;
use App\Models\Game;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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

    private function isEquivalent($value1, $value2)
    {
        return $value1 === $value2
            || ($value1 === '' && ($value2 === null || $value2 === []))
            || ($value1 === null && ($value2 === '' || $value2 === []))
            || (is_array($value1) && empty($value1) && ($value2 === '' || $value2 === null))
            || (is_array($value2) && empty($value2) && ($value1 === '' || $value1 === null));
    }

    public function updateMoveData(Request $request, $gameId, $characterId)
    {
        $request->validate([
            '*.id' => 'required|integer|exists:character_moves,id',
            '*.resource_gain' => 'sometimes|nullable|numeric',
            '*.resource_cost' => 'sometimes|nullable|numeric',
            '*.meter_cost' => 'sometimes|nullable|numeric',
            '*.meter_gain' => 'sometimes|nullable|numeric',
            '*.hit_count' => 'sometimes|nullable|integer|min:0',
            '*.ex_hit_count' => 'sometimes|nullable|integer|min:0',
            '*.damage' => 'sometimes|nullable|numeric|min:0',
            '*.category' => 'sometimes|nullable|string|max:255',
            '*.type' => 'sometimes|nullable|string|max:255',
            '*.startup_frames' => 'sometimes|nullable|integer|min:0',
            '*.active_frames' => 'sometimes|nullable|integer|min:0',
            '*.recovery_frames' => 'sometimes|nullable|integer|min:0',
            '*.frames_on_hit' => 'sometimes|nullable|integer',
            '*.frames_on_block' => 'sometimes|nullable|integer',
            '*.frames_on_counter_hit' => 'sometimes|nullable|integer',
        ]);

        $game = Game::findOrFail($gameId);
        $character = Character::findOrFail($characterId);
        $characterMoves = $character->moves;

        $movesToUpdate = $request->all();
        $updatedMoves = [];
        $propertiesOfInterest = [
            "resource_gain", "resource_cost", "meter_cost", "meter_gain",
            "hit_count", "ex_hit_count", "damage", "category", "type",
            "startup_frames", "active_frames", "recovery_frames",
            "frames_on_hit", "frames_on_block", "frames_on_counter_hit",
        ];

        // Update move data in DB
        $updates = [];
        foreach ($movesToUpdate as $characterMove) {
            $characterMoveModel = CharacterMove::where('character_id', $characterId)->where('id', $characterMove['id'])->firstOrFail();

            $moveDataHasChanged = false;
            if ($characterMoveModel) {
                foreach ($propertiesOfInterest as $property) {
                    if (!$this->isEquivalent($characterMoveModel->$property, $characterMove[$property])) {
                        $moveDataHasChanged = true;
                        $updates[$property] = $characterMove[$property];
                    }
                }
            }

            if ($moveDataHasChanged) {
                array_push($updatedMoves, $characterMove);
                $characterMoveModel->update($updates);
            }
        }

        return response()->json([
            'updated_moves' => $updatedMoves
        ]);
    }
}
