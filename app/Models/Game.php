<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $guarded = [];

    // public function user()
    // {
    //     return $this->belongsToMany(User::class);
    // }

    public function characters()
    {
        return $this->hasMany(Character::class);
    }

    public function directionalInputs()
    {
        return $this->belongsToMany(DirectionalInput::class);
    }

    public function buttons()
    {
        return $this->hasMany(AttackButton::class);
    }

    public function notations()
    {
        return $this->hasMany(GameNotation::class);
    }
}
