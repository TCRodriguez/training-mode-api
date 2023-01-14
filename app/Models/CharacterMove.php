<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterMove extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function character()
    {
        return $this->belongsTo(Character::class, 'character_id');
    }

    public function directionalInputs()
    {
        return $this->belongsToMany(DirectionalInput::class);
    }

    public function attackButtons()
    {
        return $this->belongsToMany(AttackButton::class);
    }

    public function combos()
    {
        return $this->belongsToMany(CharacterCombo::class);
    }
}
