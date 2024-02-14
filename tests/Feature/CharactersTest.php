<?php

namespace Tests\Feature;

use App\Models\Character;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CharactersTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_retrieve_all_characters_for_game()
    {
        Character::factory()->count(3)->create();

        $response = $this->getJson('v1/games/1/characters/guest');
        var_dump($response->getContent());

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'archetype',
            ]
        ]);
    }
}
