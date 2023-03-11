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
        return $this->hasMany(Move::class);
    }

    /**
     * ? Is this possible to implement even when not all characters have their own specific notations?
     * ? Does including this Eloqent relationship break if some characters don't have them?
     */
    public function notations()
    {
        return $this->hasMany(GameNotation::class);
    }
}
