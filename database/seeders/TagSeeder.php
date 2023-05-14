<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tags')->delete();
        // $tags = ["tag1", "tag2", "tag3"];
        // foreach($tags as $tag) {
        //     Tag::create([
        //         "name" => $tag
        //     ]);
        // }
        // * add json stuff here
        $json = File::get("storage/gameData/Tags.json");
        $tags = json_decode($json);
        foreach($tags as $tag) {
            
            $gameModel = Game::where('title', $tag->game)->firstOrFail()->pluck('id');
            $gameId = Arr::get($gameModel, 0);
            Tag::create([
                "name" => $tag->name,
                "user_id" => 1,
                "game_id" => $gameId
            ]);
        }
    }
}
