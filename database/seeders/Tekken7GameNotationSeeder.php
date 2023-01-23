<?php

namespace Database\Seeders;

use App\Models\AttackButton;
use App\Models\DirectionalInput;
use App\Models\Game;
use App\Models\GameNotation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Tekken7GameNotationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table('game_notations')->delete();
        $json = File::get("storage/gameData/T/Tekken 7/Tekken7Notations.json");
        $gameNotations = json_decode($json);
        foreach($gameNotations as $gameNotation) {

            $gameId = Game::where('title', $gameNotation->game)->firstOrFail()->pluck('id');



            $gameNotationModel = GameNotation::create([
                "notation" => $gameNotation->text,
                "description" => $gameNotation->description,
                "game_id" => Arr::get($gameId, 0),
                // "directional_input_id" => Arr::get($directionalInputId, 0),
                // "attack_button_id" => Arr::get($attackButtonId, 0)
            ]);

            // DB::table('directional_input_game_notation')->insert([
            //     'directional_input_id' => $gameNotation->directional_inputs
            // ])
            foreach($gameNotation->directional_inputs as $directionalInput) {
                // echo $directionalInput;
                $directionalInputModel = DirectionalInput::where('direction', $directionalInput)->first();
                $directionalInputId = $directionalInputModel !== null ? $directionalInputModel->id : null;
                // echo $directionalInputModel;
                
                if($directionalInputId !== null) {
                    $now = now();
                    DB::insert('insert into directional_input_game_notation (directional_input_id, game_notation_id, created_at, updated_at) values (?, ?, ?, ?)', [$directionalInputId, $gameNotationModel->id, $now, $now]);
                }
            }

            foreach($gameNotation->attack_buttons as $attackButton) {
                $attackButtonModel = AttackButton::where('name', $attackButton)->first();
                $attackButtonId = $attackButtonModel !== null ? $attackButtonModel->id : null;

                if($attackButtonId !== null) {
                    $now = now();
                    DB::insert('insert into attack_button_game_notation (attack_button_id, game_notation_id, created_at, updated_at) values (?, ?, ?, ?)', [$attackButtonId, $gameNotationModel->id, $now, $now]);
                }
            }
        }
    }
}
