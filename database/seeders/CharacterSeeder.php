<?php

namespace Database\Seeders;

use App\Models\AttackButton;
use App\Models\Character;
use App\Models\CharacterMove;
use App\Models\CharacterMoveCondition;
use App\Models\DirectionalInput;
use App\Models\Game;
use App\Models\GameNotation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('characters')->delete();
        // $json = File::get("storage/gameData/T/Tekken 7/Tekken7Characters.json");
        // $characterDataFilesPath = 'storage/gameData/T/Tekken 7/characters';
        // $characterDataFiles = array_diff(scandir($characterDataFilesPath), array('..', '.'));
        $characterDataFiles = glob('storage/gameData/*/*/characters/*');
        foreach($characterDataFiles as $file) {
            $json = File::get($file);

            $characters = json_decode($json);
            $now = now();
            
            foreach($characters as $character) {

                $gameModel = Game::where('title', $character->game)->firstOrFail();
                // $gameId = Arr::get($gameModel, 0);
                $gameId = $gameModel->id;
                $characterModel = Character::create([
                    "name" => $character->name,
                    "archetype" => $character->archetype,
                    "game_id" => $gameId
                ]);
                
                $characterNotations = $character->notations;
                foreach($characterNotations as $notation => $description) {

                    // DB::insert('insert into game_notations (notation, description, game_id, character_id, created_at, updated_at) values (?, ?, ?, ?, ?, ?)', [$notation, $description, $gameId, $characterModel->id, $now, $now]);
                    DB::insert(
                        'insert into game_notations (notation, description, game_id, character_id, notations_group, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', 
                        [
                            $notation, 
                            $description, 
                            $gameId, 
                            $characterModel->id, 
                            $character->notations_group, 
                            $now, 
                            $now
                        ]
                    );
    
                }

                $characterModel = Character::where('name', $character->name)->firstOrFail();
                foreach($character->moves as $move) {
                    // var_dump($character);
                    if($move->name !== '') {
                        // * Add game_id to this table
                        DB::insert(
                            'insert into character_moves (name, character_id, game_id, resource_gain, resource_cost, meter_cost, meter_gain, hit_count, ex_hit_count, damage, category, type, startup_frames, active_frames, recovery_frames, frames_on_hit, frames_on_block, frames_on_counter_hit, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $move->name,
                                $characterModel->id,
                                $gameId,
                                isset($move->resource_gain) ? $move->resource_gain : null,
                                isset($move->resource_cost) ? $move->resource_cost : null,
                                isset($move->meter_cost) ? $move->meter_cost : null,
                                isset($move->meter_gain) ? $move->meter_gain : null,
                                isset($move->hit_count) ? $move->hit_count : null,
                                isset($move->ex_hit_count) ? $move->ex_hit_count : null,
                                $move->damage,
                                $move->category,
                                $move->type,
                                $move->startup_frames,
                                $move->active_frames,
                                $move->recovery_frames,
                                $move->frames_on_hit,
                                $move->frames_on_block,
                                $move->frames_on_counter_hit,
                                $now,
                                $now
                            ]
                        );

                        /**
                         * * Get DirectionalInput model to access data
                         */

                        $characterMoveModel = CharacterMove::where('name', $move->name)
                                                            ->where('character_id', $characterModel->id)
                                                            ->firstOrFail();
                        // dd($characterMoveModel->id);
                        $characterMoveId = $characterMoveModel->id;

                        foreach($move->inputs as $index => $input) {
                            $orderInMove = $index + 1;
                            if($input->group === 'directions') {
                                $directionalInputModel = DirectionalInput::where('direction', $input->input)->pluck('id');
                                $directionalInputId = Arr::get($directionalInputModel, 0);
                                // var_dump($input);
                                DB::insert(
                                    'insert into character_move_directional_input (character_move_id, directional_input_id, order_in_move, created_at, updated_at) values (?, ?, ?, ?, ?)',
                                    [
                                        $characterMoveId, 
                                        $directionalInputId === null ? 9 : $directionalInputId,
                                        $orderInMove,
                                        $now,
                                        $now
                                    ]
                                );
                            };

                            if($input->group === 'attacks') {
                                $attackButtonModel = AttackButton::where('name', $input->input)->pluck('id');
                                $attackButtonId = Arr::get($attackButtonModel, 0);
                                // dd($input);
                                // var_dump($move->name);
                                // var_dump($input);
                                DB::insert(
                                    'insert into attack_button_character_move (attack_button_id, character_move_id, order_in_move, created_at, updated_at) values (?, ?, ?, ?, ?)',
                                    [
                                        $attackButtonId,
                                        $characterMoveId, 
                                        $orderInMove,
                                        $now, 
                                        $now
                                    ]
                                );
                            };
                            
                            if($input->group === 'notations') {
                                // dd($input);
                                $gameNotationModel = GameNotation::where('game_id', $gameId)
                                    ->where('description', $input->input)
                                    ->pluck('id');
                                // dd($gameNotationModel);
                                $gameNotationId = Arr::get($gameNotationModel, 0);
                                // dd($gameNotationId);
                                // var_dump($input);
                                DB::insert(
                                    'insert into character_move_game_notation (character_move_id, game_notation_id, order_in_move, created_at, updated_at) values (?, ?, ?, ?, ?)',
                                    [
                                        $characterMoveId, 
                                        $gameNotationId,
                                        $orderInMove,
                                        $now, 
                                        $now
                                    ]
                                );
                            }
                        }
                        // var_dump($notationString);
                        foreach($move->zones as $index => $zone) {
                            // echo $zone;
                            // $gameId = Arr::get($gameModel, 0);

                            $zoneData = DB::table('hit_zones')
                                    ->where('zone', $zone)
                                    ->get()
                                    ->pluck('id');

                            $zoneId = Arr::get($zoneData, 0);
                            $orderInZoneList = $index + 1;
                            // dd($move->name);
                            if($zone !== '') {
                                DB::insert(
                                    'insert into character_move_hit_zone (character_move_id, hit_zone_id, order_in_zone_list, created_at, updated_at) values (?, ?, ?, ?, ?)',
                                
                                    [
                                        $characterMoveId,
                                        $zoneId === null ? 4 : $zoneId,
                                        $orderInZoneList,
                                        $now,
                                        $now
                                    ]
                                );
                            }
                        }
                        
                        if(isset($move->conditions)) {
                            foreach($move->conditions as $condition) {
                                if($condition !== '') {
                                    $characterMoveCondition = CharacterMoveCondition::firstOrCreate([
                                        'condition' => $condition,
                                        // 'character_move_id' => $characterMoveId,
                                        'game_id' => $gameId

                                    ]);

                                    DB::insert(
                                        'insert into character_move_character_move_condition (character_move_id, character_move_condition_id, created_at, updated_at) values (?, ?, ?, ?)', 
                                        [
                                            $characterMoveId,
                                            $characterMoveCondition->id,
                                            $now,
                                            $now
                                        ]
                                    );
                                }
                            }
                        }
                    }   
                }

                // Doing this as it's own loop since follow ups depend on all moves existing before creating the associations
                foreach($character->moves as $move) {
                    if(isset($move->follow_up_to)) {
                        foreach($move->follow_up_to as $parentName) {

                            if($parentName !== '') {
                                var_dump($parentName);
                                $parentCharacterMove = CharacterMove::where('name', $parentName)->firstOrFail();
                                // var_dump($parentCharacterMove->name);
                                $childCharacterMove = CharacterMove::where('name', $move->name)->firstOrFail();

                                // $childCharacterMove->followUps()->associate($parentCharacterMove);
                                // $childCharacterMove->save();
                                $parentCharacterMove->followUps()->attach($childCharacterMove->id);
                                $parentCharacterMove->save();
                                
                            }
                        }
                    }
                }


            }
        }
    }
}
