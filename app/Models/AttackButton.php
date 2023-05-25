<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttackButton extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    // public function notation()
    // {
    //     return $this->hasOne(GameNotation::class, 'attack_button_id');
    // }
    public function notations()
    {
        return $this->belongsToMany(GameNotation::class, 'attack_button_game_notation', 'attack_button_id', 'game_notation_id');
    }

    // public function icon()
    // {
    //     return $this->hasOne(AttackButton)
    // }
}
