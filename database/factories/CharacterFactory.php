<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Character>
 */
class CharacterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $game = Game::factory()->create();
        return [
            'name' => $this->faker->name,
            'archetype' => $this->faker->word,
            'game_id' => $game->id,
        ];
    }
}
