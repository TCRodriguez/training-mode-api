<?php

namespace Database\Seeders;

use App\Models\AttackButton;
use App\Models\Character;
use App\Models\CharacterMove;
use App\Models\DirectionalInput;
use App\Models\Game;
use App\Models\GameNotation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Tekken7CharacterSeeder extends Seeder
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
        $characterDataFilesPath = 'storage/gameData/T/Tekken 7/characters';
        $characterDataFiles = array_diff(scandir($characterDataFilesPath), array('..', '.'));
        foreach($characterDataFiles as $file) {
            $json = File::get("storage/gameData/T/Tekken 7/characters/{$file}");

            $characters = json_decode($json);
            $now = now();
            
            foreach($characters as $character) {

                $gameModel = Game::where('title', $character->game)->firstOrFail()->pluck('id');
                $gameId = Arr::get($gameModel, 0);
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
                            'insert into character_moves (name, character_id, damage, category, type, startup_frames, active_frames, recovery_frames, frames_on_hit, frames_on_block, frames_on_counter_hit, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
                            [
                                $move->name,
                                $characterModel->id,
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

                        $notationString = [];
                        isset($move->notation_string) ? array_push($notationString, $move->notation_string) : null;
                        foreach($move->inputs as $index => $input) {
                            // array_push($notationString, $input->notation_string);
                            // $directionalInputModel = null;
                            // dd($input->group);
                            // $group = $input->group;
                            // var_dump($group);
                            // echo $input->input;
                            // echo "\n";
                            // echo array_search($input, $move->inputs) + 1;
                            // echo "\n";


                            $orderInMove = $index + 1;
                            if($input->group === 'directions') {
                                $directionalInputModel = DirectionalInput::where('direction', $input->input)->pluck('id');
                                $directionalInputId = Arr::get($directionalInputModel, 0);
                                var_dump($input);
                                DB::insert(
                                    'insert into character_move_directional_input (character_move_id, directional_input_id, order_in_move, created_at, updated_at) values (?, ?, ?, ?, ?)',
                                    [
                                        $characterMoveId, 
                                        $directionalInputId,
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
                            echo $zone;
                            // $gameId = Arr::get($gameModel, 0);

                            $zoneData = DB::table('hit_zones')
                                    ->where('zone', $zone)
                                    ->get()
                                    ->pluck('id');

                            $zoneId = Arr::get($zoneData, 0);
                            $orderInZoneList = $index + 1;
                            DB::insert(
                                'insert into character_move_hit_zone (character_move_id, hit_zone_id, order_in_zone_list, created_at, updated_at) values (?, ?, ?, ?, ?)',
                            
                                [
                                    $characterMoveId,
                                    $zoneId,
                                    $orderInZoneList,
                                    $now,
                                    $now
                                ]
                                );
                        }

                    }   
                }
            }
        }
    }
}
