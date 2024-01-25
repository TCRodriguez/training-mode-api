<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AddBaseGameData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:add-base-game-data
                            {game : The game you want to add the base game data for.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This group command adds the base game, character, and notations data to the DB.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->call('game:create', [
            'game' => $this->argument('game')
        ]);
        $this->call('add:attack-buttons', [
            'game' => $this->argument('game')
        ]);
        $this->call('add:game-notations', [
            'game' => $this->argument('game')
        ]);
        $this->call('add:characters', [
            'game' => $this->argument('game')
        ]);

        return Command::SUCCESS;
    }
}
