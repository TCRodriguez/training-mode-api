<?php

namespace App\Console\Commands;

use App\Models\AttackButton;
use App\Models\Character;
use App\Models\DirectionalInput;
use App\Models\Game;
use App\Models\GameNotation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AddCharacterNotations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:add-character-notations
                            {game : The game you want to add the notations for. (use the abbreviation like "SF6" for "Street Fighter 6") }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command adds character-specific notations for all the game\'s characters to the DB.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        $input = $this->argument('game');
        $this->info("This is what you passed in: {$input}");

        try {
            $gameArgument = strtolower($this->argument('game'));
            $game = Game::where('abbreviation', $gameArgument)->firstOrFail();
        } catch (\Throwable $th) {
            //throw $th;
            $this->error("Game not found.");
            return Command::FAILURE;
        }


        $characterNotationsFile = glob("storage/gameData/*/*/characters/({$this->argument('game')})*.json");
        if(count($characterNotationsFile) === 0) {
            $this->error('No files found.');
            return Command::FAILURE;
        }
        
        if($this->confirm("You're about to add the character-specific notations for <fg=yellow>{$game->title}</> to the DB. Continue?")) {
            foreach($characterNotationsFile as $file) {
                $json = File::get($file);
                $characters = json_decode($json);

                foreach($characters as $character) {
                    try {
                        $characterModel = Character::where('name', $character->name)->where('game_id', $game->id)->firstOrFail();
                    } catch (\Throwable $th) {
                        $this->error("Character {$character->name} not found.");
                        return Command::FAILURE;
                    }

                    $characterNotations = $character->notations;
                    foreach($characterNotations as $notation => $description) {
                        $notationExistenceCheck = GameNotation::where('notation', $notation)->where('game_id', $game->id)->where('character_id', $characterModel->id)->doesntExist();
                        if($notationExistenceCheck) {
                            DB::insert(
                                'insert into game_notations (notation, description, game_id, character_id, notations_group, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', 
                                [
                                    $notation, 
                                    $description, 
                                    $game->id, 
                                    $characterModel->id, 
                                    $character->notations_group, 
                                    now(), 
                                    now()
                                ]
                            );

                            $this->info("<fg=yellow>{$characterModel->name}'s</> notation <fg=yellow>{$notation}: {$description}</> added to the database."); 
                        } else {
                            $this->error("Notation <fg=yellow>{$notation}</> already exists in the database.");
                        }
                    }
                }
            }
        }
        return Command::SUCCESS;
    }
}
