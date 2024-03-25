<?php

namespace App\Console\Commands;

use App\Models\Game;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class AddNewGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:create
                            {game : The game you wish to add to the DB.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserts a new entry for specified game';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $gameToAdd = $this->argument('game');
        $json = File::get("storage/gameData/Games.json");
        $games = json_decode($json);

        $newGameObjectArray = array_filter($games, function ($game) use ($gameToAdd) {
            return $game->title == $gameToAdd;
        });

        $gameExistenceCheck = Game::where('title', $gameToAdd)->doesntExist();
        if ($gameExistenceCheck) {
            $newGameObject = reset($newGameObjectArray);
            if ($this->confirm("You're about to add {$gameToAdd} to the database. Continue?")) {
                Game::create([
                    "title" => $newGameObject->title,
                    "abbreviation" => $newGameObject->abbreviation,
                    "buttons" => $newGameObject->buttons
                ]);
                $this->info("{$gameToAdd} successfully added to the database.");
                return Command::SUCCESS;
            }
        } else {
            $this->error("{$gameToAdd} not added because it already exists in the DB.");
            return Command::FAILURE;
        }
    }
}
