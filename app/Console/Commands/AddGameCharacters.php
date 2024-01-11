<?php

namespace App\Console\Commands;

use App\Models\Character;
use App\Models\Game;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AddGameCharacters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:characters
                            {game : The game for which you want to add the characters of.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command adds the base character data (no moves) for all characters to the DB.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {



        $input = $this->argument('game');
        $this->info("This is what you passed in: {$input}");

        var_dump(glob("storage/gameData/*/{$this->argument('game')}/characters"));

        $characterDataFiles = glob("storage/gameData/*/{$this->argument('game')}/characters/*.json");
        var_dump($characterDataFiles);

        if(count($characterDataFiles) === 0) {
            $this->error('No files found.');
            return Command::FAILURE;
        }


        if($this->confirm("You're about to add all base character data for {$this->argument('game')} to the DB. Continue?")) {
            foreach($characterDataFiles as $file) {
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

                // var_dump($gameModel->title);
                $characterExistenceCheck = Character::where('name', $characterJSON->name)->doesntExist();
                if($characterExistenceCheck) {
                    $characterModel = Character::make([
                        "name" => $characterJSON->name,
                        "archetype" => $characterJSON->archetype,
                        "game_id" => $gameId
                    ]);
                    // var_dump($characterModel->name);
                    $characterModel->save();
                } else {
                    $this->error("Character {$characterJSON->name} not added to the database because they already exist.");
                    return Command::FAILURE;
                }
                
                // $characterNotations = $characterJSON->notations;
                // if(count($characterNotations) > 0) {
                //     foreach($characterNotations as $notation => $description) {
                //         DB::insert(
                //             'insert into game_notations (notation, description, game_id, character_id, notations_group, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', 
                //             [
                //                 $notation, 
                //                 $description, 
                //                 $gameId, 
                //                 $characterModel->id, 
                //                 $characterJSON->notations_group, 
                //                 $now, 
                //                 $now
                //             ]
                //         );
                //     }
                // }
            }
        }

        return Command::SUCCESS;
    }
}
