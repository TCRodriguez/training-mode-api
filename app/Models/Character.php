<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function moves()
    {
        return $this->hasMany(CharacterMove::class);
    }

    public function notations()
    {
        return $this->hasMany(GameNotation::class);
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
    }
}
