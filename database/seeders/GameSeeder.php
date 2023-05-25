<?php

namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

// use Illuminate\Filesystem\Filesystem;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('games')->delete();
        $json = File::get("storage/gameData/Games.json");
        $games = json_decode($json);
        foreach($games as $game) {
            Game::create([
                "title" => $game->title,
                "abbreviation" => $game->abbreviation
            ]);
        }
    }
}