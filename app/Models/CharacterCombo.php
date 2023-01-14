<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterCombo extends Model
{
    use HasFactory;

    protected $guarded =[];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function character()
    {
        return $this->belongsTo(Character::class, 'character_id');
    }

    public function moves()
    {
        return $this->belongsToMany(CharacterMove::class);
    }

    public function directionalInputs()
    {
        return $this->belongsToMany(DirectionalInput::class);
    }
    
    public function attackButtons()
    {
        return $this->belongsToMany(AttackButton::class);
    }
}
