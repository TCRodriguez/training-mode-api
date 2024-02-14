<?php

namespace App\Console\Commands;

use App\Models\AttackButton;
use App\Models\DirectionalInput;
use App\Models\Game;
use App\Models\GameNotation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AddGameNotations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:add-game-notations
                            {game : The game you want to add the notations for. (use the abbreviation like "SF6" for "Street Fighter 6") }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command adds the notations for the game to the DB.';

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
            //code...
            $gameArgument = strtolower($this->argument('game'));
            // dd($gameArgument);
            var_dump($gameArgument);
            $game = Game::where('abbreviation', $gameArgument)->firstOrFail();
        } catch (\Throwable $th) {
            //throw $th;
            $this->error("Game not found.");
            return Command::FAILURE;
        }

        $gameNotationsFile = glob("storage/gameData/*/*/{$this->argument('game')}*Notations.json");
        var_dump($gameNotationsFile);

        if(count($gameNotationsFile) === 0) {
            $this->error('No files found.');
            return Command::FAILURE;
        }



        if($this->confirm("You're about to add all notations data for {$this->argument('game')} to the DB. Continue?")) {
            foreach($gameNotationsFile as $file) {
                $json = File::get($file);
                $gameNotations = json_decode($json);

                foreach($gameNotations as $notation) {
                    $notationExistenceCheck = GameNotation::where('notation', $notation->text)->where('game_id', $game->id)->doesntExist();
                    var_dump($notationExistenceCheck);
                    if($notationExistenceCheck) {
                        $gameModel = Game::where('title', $notation->game)->firstOrFail();
                        $gameId = $gameModel->id;
                        $gameNotationModel = GameNotation::create([
                            "notation" => $notation->text,
                            "description" => $notation->description,
                            "game_id" => $gameId,
                            "notations_group" => $notation->group,
                            "icon_file_name" => $notation->icon,
                        ]);
                        // var_dump("{$gameNotationModel->notation}: {$gameNotationModel->description}");
                        $this->info("Notation <fg=yellow>{$gameNotationModel->notation}: {$gameNotationModel->description}</> added to the database.");
    
                        foreach($notation->directional_inputs as $directionalInput) {
                            $directionalInputModel = DirectionalInput::where('direction', $directionalInput)->first();
                            $directionalInputId = $directionalInputModel !== null ? $directionalInputModel->id : null;
                            
                            if($directionalInputId !== null) {
                                $now = now();
                                DB::insert('insert into directional_input_game_notation (directional_input_id, game_notation_id, created_at, updated_at) values (?, ?, ?, ?)', [$directionalInputId, $gameNotationModel->id, $now, $now]);
                            }
                        }
    
                        foreach($notation->attack_buttons as $attackButton) {
                            $attackButtonModel = AttackButton::where('name', $attackButton)->where('game_id', $gameId)->first();
                            $attackButtonId = $attackButtonModel !== null ? $attackButtonModel->id : null;
    
                            if($attackButtonId !== null) {
                                $now = now();
                                // DB::insert('insert into attack_button_game_notation (attack_button_id, game_notation_id, created_at, updated_at) values (?, ?, ?, ?)', [$attackButtonId, $gameNotationModel->id, $now, $now]);
                            }
                        }
                    } else {
                        $this->error("Notation {$notation->text} not added to the database because it already exists.");
                    }
                    
                }
            }
        }
        return Command::SUCCESS;
    }
}
