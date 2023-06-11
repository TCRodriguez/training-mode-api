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
        // return $request->user();
        // $clients = Client::where('trainer_id', $request->user()->id)->paginate(60);
        // $characterCombos = CharacterCombo::where('character_id', $characterId)
        //     ->with('directionalInputs')
        //     ->with('attackButtons')
        //     ->with('tags')
        //     ->get();
        $characterCombos = CharacterCombo::where('user_id', $request->user()->id)->where('character_id', $characterId)
            ->with('directionalInputs')
            ->with('attackButtons')
            ->with('notations')
            ->with('tags')
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
            // 'user_id' => $request->user()->id,
            'game_id' => $gameId,
            'user_id' => Auth::id(),
            // 'trainer_id' => $request->input('id'),
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




        // ! character_combo_game_notation (need to create this table)
            
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
        // $testArray = [];
        // foreach($request->inputs as $index => $input) {
        //     if($input['category'] === 'directional-inputs') {
        //         array_push($testArray, $input['category']);
        //     }
        // }
        // return $testArray;
        // return 'PUT Character Combo HIT';
        $characterCombo = CharacterCombo::with('directionalInputs')
            ->with('attackButtons')
            ->with('notations')
            ->findOrFail($comboId);

        // ? Do we first need to remove all pivot table records?
        // ? Do we use detach?
        // * We can use sync()...
        $characterCombo->directionalInputs()->detach();
        $characterCombo->notations()->detach();
        $characterCombo->attackButtons()->detach();
        $characterCombo->touch();


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




        // ! character_combo_game_notation (need to create this table)
            
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
        // return 'DELETE Character Combo HIT';
        /**
         * ? Perhaps we `detach()` all of the tags first before deleting the combo?
         * ? This would then handle removing the relevant records from the `taggables
         * ? table.
         */
        // $characterCombo = CharacterCombo::findOrFail($comboId);
        // $tag = Tag::where('id', $tagId)->firstOrFail();

        // * Get tag IDs of the combo we're about to delete
        // * Detach these tags
        // * Delete the combo


        $characterCombo = CharacterCombo::where('id', $comboId)->firstOrFail();
        $tags = $characterCombo->tags;
        foreach($tags as $tag) {
            Tagger::untagCharacterMove($gameId, $characterCombo, array($tag->name));
        };

        // $characterCombo = CharacterCombo::where('id', $comboId)
        //     ->with('tags')
        //     ->firstOrFail();

        $characterCombo->delete();
        
        return $characterCombo;
    }

    public function addCharacterComboTag(Request $request, $gameId, $characterId, $characterComboId)
    {
        // return $request;
        $characterComboTags = $request->tags;

        $characterCombo = CharacterCombo::where('id', $characterComboId)->firstOrFail();

        Tagger::tagCharacterCombo($gameId, $characterCombo, $characterComboTags);

        $characterCombo = CharacterCombo::where('id', $characterComboId)
                        ->with('tags')
                        ->firstOrFail();

        return $characterCombo;

    }
    
    public function removeCharacterComboTag(Request $request, $gameId, $characterId, $characterComboId, $tagId)
    {
        $tag = Tag::where('id', $tagId)->firstOrFail();

        // dd($tag);
        $characterCombo = CharacterCombo::where('id', $characterComboId)->firstOrFail();
        Tagger::untagCharacterMove($gameId, $characterCombo, array($tag->name));

        $characterCombo = CharacterCombo::where('id', $characterComboId)
            ->with('tags')
            ->firstOrFail();

        return $characterCombo;

    }
}
