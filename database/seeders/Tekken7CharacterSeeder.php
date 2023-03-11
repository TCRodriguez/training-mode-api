<?php

namespace Database\Seeders;

use App\Models\Character;
use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Tekken7CharacterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('characters')->delete();
        $json = File::get("storage/gameData/T/Tekken 7/Tekken7Characters.json");
        $characters = json_decode($json);
        $now = now();
        
        foreach($characters as $character) {

            $gameModel = Game::where('title', $character->game)->firstOrFail()->pluck('id');
            $gameId = Arr::get($gameModel, 0);
            $characterModel = Character::create([
                "name" => $character->name,
                "archetype" => $character->archetype,
                "game_id" => $gameId
            ]);

            // DB::insert('insert into game_notations (group) values (?)', [$character->group]);



            $characterNotations = $character->notations;
           
            foreach($characterNotations as $notation => $description) {

                // DB::insert('insert into game_notations (notation, description, game_id, character_id, created_at, updated_at) values (?, ?, ?, ?, ?, ?)', [$notation, $description, $gameId, $characterModel->id, $now, $now]);
                DB::insert('insert into game_notations (notation, description, game_id, character_id, notations_group, created_at, updated_at) values (?, ?, ?, ?, ?, ?, ?)', [$notation, $description, $gameId, $characterModel->id, $character->notations_group, $now, $now]);
 
            }
            // GameNotation::where('')
            // DB::insert('insert into game_notations (notation, description, game_id) values (?, ?, ?)', [$notation]);
        }
    }
}
