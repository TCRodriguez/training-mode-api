<?php

namespace Database\Seeders;

use App\Models\DirectionalInput;
use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DirectionalInputSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('directional_inputs')->delete();
        $json = File::get("storage/gameData/DirectionalInputs.json");
        $directionalInputs = json_decode($json);
        foreach($directionalInputs as $directionalInput) {
            DirectionalInput::create([
                "direction" => $directionalInput->direction,
                "numpad_notation" => $directionalInput->numpad_notation,
                "game_shorthand" => $directionalInput->game_shorthand,
            ]);

            $directionalInputModel = DirectionalInput::where('direction', $directionalInput->direction)->first();
            $directionalInputId = $directionalInputModel !== null ? $directionalInputModel->id : null;

            $now = now();
            foreach($directionalInput->icon as $icon) {
                $gameModel = Game::where('title', $icon->game)->firstOrFail();
                $gameId = $gameModel->id;

                if($directionalInputId !== null) {
                    DB::insert('insert into directional_input_icons (icon_file_name, directional_input_id, game_id, created_at, updated_at) values (?, ?, ?, ?, ?)',
                    [
                        $icon->icon_file_name, 
                        $directionalInputId, 
                        $gameId, 
                        $now, 
                        $now
                    ]);
                }
            }
        }
    }
}
