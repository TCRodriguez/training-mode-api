<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('characters')->delete();
        $json = File::get("storage/Characters.json");
        $characters = json_decode($json);
        foreach($characters as $character) {

            $gameId = Game::where('title', $character->game)->firstOrFail()->pluck('id');
            Character::create([
                "name" => $character->name,
                "archetype" => $character->archetype,
                // ? How can we dynamically grab the relevant game_id here?
                "game_id" => Arr::get($gameId, 0)
            ]);
            $id = Arr::get($gameId, 0);
            echo $id;
        }
    }
}
