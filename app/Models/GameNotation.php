<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameNotation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function directionalInputs()
    {
        return $this->belongsToMany(DirectionalInput::class, 'directional_input_game_notation', 'game_notation_id', 'directional_input_id');
    }

    public function attackButtons()
    {
        return $this->belongsToMany(AttackButton::class, 'attack_button_game_notation', 'game_notation_id', 'attack_button_id');
    }
}
