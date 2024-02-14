<?php

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GamesTest extends TestCase
{

    use RefreshDatabase;

    public function test_guest_can_retrieve_all_games()
    {
        $games = Game::factory()->count(3)->create();

        $response = $this->getJson('v1/games/guest');
        var_dump($response->getContent());

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'title',
                'abbreviation',
                'buttons',
            ]
        ]);
    }
}
