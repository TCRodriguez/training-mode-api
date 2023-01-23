<?php

namespace Database\Seeders;

use App\Models\AttackButton;
use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Tekken7AttackButtonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attack_buttons')->delete();
        $json = File::get("storage/gameData/T/Tekken 7/Tekken7AttackButtons.json");
        $attackButtons = json_decode($json);
        foreach($attackButtons as $attackButton) {

            $gameId = Game::where('title', $attackButton->game)->firstOrFail()->pluck('id');
            AttackButton::create([
                "name" => $attackButton->name,
                // ? How can we dynamically grab the relevant game_id here?
                "game_id" => Arr::get($gameId, 0)
            ]);
        }
    }
}
