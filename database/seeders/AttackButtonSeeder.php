<?php

namespace Database\Seeders;

use App\Models\AttackButton;
use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AttackButtonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('attack_buttons')->delete();

        $attackButtonFiles = glob('storage/gameData/*/*/*AttackButtons*');

        // $attackButtons = json_decode($);
        foreach($attackButtonFiles as $file) {
            $json = File::get($file);
            $attackButtons = json_decode($json);

            foreach($attackButtons as $attackButton) {

                $gameModel = Game::where('title', $attackButton->game)->firstOrFail();
                $gameId = $gameModel->id;
                AttackButton::create([
                    "name" => $attackButton->name,
                    "button_count" => $attackButton->button_count,
                    // ? How can we dynamically grab the relevant game_id here?
                    "game_id" => $gameId,
                    "icon_file_name" => $attackButton->icon,
                ]);
            }
        }

    }
}
