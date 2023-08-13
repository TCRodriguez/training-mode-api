<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\AttackButton;
use App\Models\CharacterCombo;
use App\Models\DirectionalInput;
use App\Models\GameNotation;
use App\Models\Tag;
use App\Utilities\Tagger;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CharacterComboController extends Controller
{
    public function index(Request $request, $gameId, $characterId)
    {
        $characterCombos = CharacterCombo::where('user_id', $request->user()->id)
            ->where('character_id', $characterId)
            ->with('directionalInputs')
            ->with('attackButtons')
            ->with('notations')
            ->with(['tags' => function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            }])
            ->with(['notes.tags' => function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            }])
            ->get();
        
        return $characterCombos;
    }

    public function show(Request $request, $gameId, $characterId, $characterCombo)
    {
        return 'GET Character Combo HIT';
    }

    public function store(Request $request, $gameId, $characterId)
    {
        $characterCombo = CharacterCombo::create([
            'game_id' => $gameId,
            'user_id' => Auth::id(),
            'character_id' => $request->input('character_id'),
            'damage' => $request->input('damage'),
            'hits' => $request->input('hits'),
        ]);

        /**
         * Insert into respective pivot tables
         */

        // character_combo_directional_input
        $now = now();
        foreach($request->inputs as $index => $input) {
            // dd($input['input']);
            $orderInCombo = $index + 1;
            // var_dump($orderInCombo);
            if($input['category'] === 'directional-inputs') {
                $directionalInputModel = DirectionalInput::where('direction', $input['direction'])->pluck('id');
                $directionalInputId = Arr::get($directionalInputModel, 0);
                DB::insert(
                    'insert into character_combo_directional_input (character_combo_id, directional_input_id, order_in_combo, created_at, updated_at) values (?, ?, ?, ?, ?)',
                    [
                        // $gameId,
                        $characterCombo->id,
                        $directionalInputId,
                        $orderInCombo,
                        $now,
                        $now
                    ]
                );
            }
            // attack_button_character_combo
            if($input['category'] === 'attack-buttons') {
                $attackButtonModel = AttackButton::where('name', $input['name'])->pluck('id');
                $attackButtonId = Arr::get($attackButtonModel, 0);
                DB::insert(
                    'insert into attack_button_character_combo (attack_button_id, character_combo_id, order_in_combo, created_at, updated_at) values (?, ?, ?, ?, ?)',
                    [
                        $attackButtonId,
                        $characterCombo->id, 
                        $orderInCombo,
                        $now, 
                        $now
                    ]
                );
            }

            // character_combo_game_notation
            if($input['category'] === 'notations' || $input['category'] === 'character-notations') {
                $gameNotationModel = GameNotation::where('notation', $input['notation'])->pluck('id');
                $gameNotationId = Arr::get($gameNotationModel, 0);
                DB::insert(
                    'insert into character_combo_game_notation (character_combo_id, game_notation_id, order_in_combo, created_at, updated_at) values (?, ?, ?, ?, ?)',
                    [
                        $characterCombo->id,
                        $gameNotationId, 
                        $orderInCombo,
                        $now, 
                        $now
                    ]
                );
            }
        // ? character_combo_character_move (Is this table necessary?)
        }

        return $characterCombo;
    }


    public function update(Request $request, $gameId, $characterId, $comboId)
    {
        $characterCombo = CharacterCombo::with('directionalInputs')
            ->with('attackButtons')
            ->with('notations')
            ->findOrFail($comboId);

        // Essentially starts the combo from a "fresh slate"
        $characterCombo->directionalInputs()->detach();
        $characterCombo->notations()->detach();
        $characterCombo->attackButtons()->detach();
        $characterCombo->touch();


        $now = now();
        foreach($request->inputs as $index => $input) {
            $orderInCombo = $index + 1;
            if($input['category'] === 'directional-inputs') {
                $directionalInputModel = DirectionalInput::where('direction', $input['direction'])->pluck('id');
                $directionalInputId = Arr::get($directionalInputModel, 0);
                DB::insert(
                    'insert into character_combo_directional_input (character_combo_id, directional_input_id, order_in_combo, created_at, updated_at) values (?, ?, ?, ?, ?)',
                    [
                        $characterCombo->id,
                        $directionalInputId,
                        $orderInCombo,
                        $now,
                        $now
                    ]
                );
            }

            // attack_button_character_combo
            if($input['category'] === 'attack-buttons') {
                $attackButtonModel = AttackButton::where('name', $input['name'])->pluck('id');
                $attackButtonId = Arr::get($attackButtonModel, 0);
                DB::insert(
                    'insert into attack_button_character_combo (attack_button_id, character_combo_id, order_in_combo, created_at, updated_at) values (?, ?, ?, ?, ?)',
                    [
                        $attackButtonId,
                        $characterCombo->id, 
                        $orderInCombo,
                        $now, 
                        $now
                    ]
                );
            }




            // character_combo_game_notation
            if($input['category'] === 'notations' || $input['category'] === 'character-notations') {
                $gameNotationModel = GameNotation::where('notation', $input['notation'])->pluck('id');
                $gameNotationId = Arr::get($gameNotationModel, 0);
                DB::insert(
                    'insert into character_combo_game_notation (character_combo_id, game_notation_id, order_in_combo, created_at, updated_at) values (?, ?, ?, ?, ?)',
                    [
                        $characterCombo->id,
                        $gameNotationId, 
                        $orderInCombo,
                        $now, 
                        $now
                    ]
                );
            }
        }

        return $characterCombo;
    }


    public function delete(Request $request, $gameId, $characterId, $comboId)
    {
        $characterCombo = CharacterCombo::where('id', $comboId)->firstOrFail();
        $tags = $characterCombo->tags;
        foreach($tags as $tag) {
            Tagger::untagCharacterMove($gameId, $characterCombo, array($tag->name));
        };

        $characterCombo->delete();
        
        return $characterCombo;
    }

    public function addCharacterComboTag(Request $request, $gameId, $characterId, $characterComboId)
    {
        $characterComboTags = $request->tags;

        $characterCombo = CharacterCombo::where('id', $characterComboId)->firstOrFail();

        Tagger::tagCharacterCombo($gameId, $characterCombo, $characterComboTags);

        $characterCombo = CharacterCombo::where('id', $characterComboId)
                        ->with(['tags' => function ($query) {
                            $query->where('user_id', Auth::id());
                        }])
                        ->firstOrFail();

        return $characterCombo;
    }
    
    public function removeCharacterComboTag(Request $request, $gameId, $characterId, $characterComboId, $tagId)
    {
        $tag = Tag::where('id', $tagId)->firstOrFail();

        $characterCombo = CharacterCombo::where('id', $characterComboId)->firstOrFail();
        Tagger::untagCharacterMove($gameId, $characterCombo, array($tag->name));

        $characterCombo = CharacterCombo::where('id', $characterComboId)
            ->with('tags')
            ->firstOrFail();

        return $characterCombo;
    }

    public function addCharacterComboNote(Request $request, $gameId, $characterId, $characterComboId)
    {
        $characterCombo = CharacterCombo::find($characterComboId);

        $note = $characterCombo->notes()->create([
            'title' => isset($request->title) ? $request->title : 'Untitled Note',
            'body' => $request->body,
            'user_id' => Auth::id(),
            'game_id' => $gameId
        ]);

        $characterCombo = CharacterCombo::with('notes')->find($characterId);

        return $characterCombo;
    }
}
