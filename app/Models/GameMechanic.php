<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameMechanic extends Model
{
    use HasFactory;

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function notation()
    {
        return $this->hasOne(GameNotation::class);
    }
}
