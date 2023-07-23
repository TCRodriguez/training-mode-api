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
        return $this->belongsToMany(
            DirectionalInput::class, 
            'character_combo_directional_input', 
            'character_combo_id', 
            'directional_input_id'
            )
            ->withPivot('order_in_combo');
    }
    
    public function attackButtons()
    {
        return $this->belongsToMany(
            AttackButton::class, 
            'attack_button_character_combo',
            'character_combo_id',
            'attack_button_id'
        )
        ->withPivot('order_in_combo');
    }

    public function notations()
    {
        return $this->belongsToMany(
            GameNotation::class,
            'character_combo_game_notation',
            'character_combo_id',
            'game_notation_id'
        )
        ->withPivot('order_in_combo');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'notable');
    }
}
