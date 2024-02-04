<?php

namespace App\Console\Commands;

use App\Models\Character;
use App\Models\Game;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class AddNewBaseCharacterEntry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:add-base-character-entry 
                            { game : The game the character is from. Use the abbreviation (e.g. "T8" for Tekken 8) };
                            { character : The name of the character to add. }';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds the base character entry to the database. No notations, moves, properties, etc.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $input = $this->argument('character');
        $this->info("This is what you passed in: {$input}");

        $characterDataFiles = glob("storage/gameData/*/*/characters/({$this->argument('game')}) {$this->argument('character')}.json");
        // var_dump(glob("storage/gameData/*/*/characters/({$this->argument('game')}) {$this->argument('character')}.json"));
        var_dump($characterDataFiles);

        if(count($characterDataFiles) === 0) {
            $this->error('No files found.');
            return Command::FAILURE;
        }
    
        foreach($characterDataFiles as $file) {

            if($this->confirm("You're about to add '{$file}' to the DB. Continue?")) {
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
                var_dump($characterModel);
            };
        }
        
        return Command::SUCCESS;
    }
}
