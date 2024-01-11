<?php

namespace App\Console\Commands;

use App\Models\AttackButton;
use App\Models\Game;
use Illuminate\Console\Command;
use Illuminate\Contracts\Mail\Attachable;
use Illuminate\Support\Facades\File;

class AddGameAttackButtons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:attack-buttons
                            {game : The game for which you want to add the attack buttons for.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command adds the attack button data for the specified game to the DB.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {


        $input = $this->argument('game');
        $this->info("This is what you passed in: {$input}");

        $attackButtonFile = glob("storage/gameData/*/{$this->argument('game')}/*AttackButtons.json");

        if(count($attackButtonFile) === 0) {
            $this->error('No files found.');
            return Command::FAILURE;
        }

        if($this->confirm("You're about to add all attack button data for <fg=yellow>{$this->argument('game')}</> to the DB. Continue?")) {

            $json = File::get($attackButtonFile[0]);
            $attackButtons = json_decode($json);

            foreach($attackButtons as $attackButton) {
                $attackButtonExistenceCheck = AttackButton::where('name', $attackButton->name)->doesntExist();

                if($attackButtonExistenceCheck) {
                    $gameModel = Game::where('title', $attackButton->game)->firstOrFail();
                    $gameId = $gameModel->id;
                    $attackButtonModel = AttackButton::create([
                        "name" => $attackButton->name,
                        "game_shorthand" => $attackButton->game_shorthand,
                        "button_count" => $attackButton->button_count,
                        "game_id" => $gameId,
                        "icon_file_name" => $attackButton->icon,
                    ]);
                } else {
                    $this->error("Attack buttons for {$attackButton->game} not added to the database because they already exist.");
                    return Command::FAILURE;
                }
            }
        }

        return Command::SUCCESS;
    }
}
