<?php

namespace App\Console\Commands;

use App\Models\AttackButton;
use App\Models\Character;
use App\Models\CharacterMove;
use App\Models\CharacterMoveCondition;
use App\Models\DirectionalInput;
use App\Models\Game;
use App\Models\GameNotation;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AddNewCharacter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // ? Should it be the entire `filePath` or just `fileName`?
    protected $signature = 'game:add-character
                            {character : The character whose data you want to add to the DB. Use character name like "Reina" }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserts new character data';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $input = $this->argument('character');
        $this->info("This is what you passed in: {$input}");

        $characterDataFiles = glob("storage/gameData/*/*/characters/*{$this->argument('character')}.json");

        if(count($characterDataFiles) === 0) {
            $this->error('No files found.');
            return Command::FAILURE;
        }
    
        foreach($characterDataFiles as $file) {

            if($this->confirm("You're about to add '{$file}' to the DB. Continue?")) {
                // TODO Add the inserts here

                $json = File::get($file);
                $characterJSONArray = json_decode($json);
                $characterJSON = reset($characterJSONArray);
                $now = now();

                try {
                    $gameModel = Game::where('title', $characterJSON->game)->firstOrFail();
                    
                } catch (\Throwable $th) {
                    // throw $th;
                    $this->error("The game {$characterJSON->game} does not yet exist. Please create it first before adding this character.");
                    return Command::FAILURE;
                }
                $gameId = $gameModel->id;

                var_dump($gameModel);
                $characterModel = Character::create([
                    "name" => $characterJSON->name,
                    "archetype" => $characterJSON->archetype,
                    "game_id" => $gameId
                ]);
                
                $characterNotations = $characterJSON->notations;
                foreach($characterNotations as $notation => $description) {
                    DB::insert(
                        'insert into game_notations (notation, description, game_id, character_id, notations_group, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', 
                        [
                            $notation, 
                            $description, 
                            $gameId, 
                            $characterModel->id, 
                            $characterJSON->notations_group, 
                            $now, 
                            $now
                        ]
                    );
                }

                $characterModel = Character::where('name', $characterJSON->name)->where('game_id', $gameId)->firstOrFail();
                foreach($characterJSON->moves as $move) {
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
                                                            ->where('game_id', $gameId)
                                                            ->firstOrFail();
                        $characterMoveId = $characterMoveModel->id;

                        foreach($move->inputs as $index => $input) {
                            $orderInMove = $index + 1;
                            if($input->group === 'directions') {
                                $directionalInputModel = DirectionalInput::where('direction', $input->input)->pluck('id');
                                $directionalInputId = Arr::get($directionalInputModel, 0);
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
                                $attackButtonModel = AttackButton::where('name', $input->input)->where('game_id', $gameId)->pluck('id');
                                $attackButtonId = Arr::get($attackButtonModel, 0);
                                var_dump($input->input);
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
                                $gameNotationModel = GameNotation::where('game_id', $gameId)
                                    ->where('description', $input->input)
                                    ->pluck('id');
                                $gameNotationId = Arr::get($gameNotationModel, 0);
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
                        foreach($move->zones as $index => $zone) {
                            $zoneData = DB::table('hit_zones')
                                    ->where('zone', $zone)
                                    ->get()
                                    ->pluck('id');

                            $zoneId = Arr::get($zoneData, 0);
                            $orderInZoneList = $index + 1;
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
                foreach($characterJSON->moves as $move) {
                    if(isset($move->follow_up_to)) {
                        foreach($move->follow_up_to as $parentName) {

                            if($parentName !== '') {
                                $parentCharacterMove = CharacterMove::where('name', $parentName)->where('game_id', $gameId)->firstOrFail();
                                $childCharacterMove = CharacterMove::where('name', $move->name)->where('game_id', $gameId)->firstOrFail();

                                $parentCharacterMove->followUps()->attach($childCharacterMove->id);
                                $parentCharacterMove->save();
                            }
                        }
                    }
                }




            };
        }
        
        return Command::SUCCESS;
    }
}
