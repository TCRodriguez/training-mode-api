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
        $json = File::get("storage/Games.json");
        $games = json_decode($json);
        foreach($games as $key => $value) {
            Game::create([
                "title" => $value->title
            ]);
        }
    }
}