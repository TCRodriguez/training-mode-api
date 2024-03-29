<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
        return $this->belongsToMany(DirectionalInput::class, 'character_move_directional_input', 'character_move_id', 'directional_input_id')->withPivot('order_in_move');
    }

    public function attackButtons()
    {
        return $this->belongsToMany(AttackButton::class, 'attack_button_character_move', 'character_move_id', 'attack_button_id')->withPivot('order_in_move');
    }

    public function combos()
    {
        return $this->belongsToMany(CharacterCombo::class);
    }

    public function notations()
    {
        return $this->belongsToMany(GameNotation::class, 'character_move_game_notation', 'character_move_id', 'game_notation_id')->withPivot('order_in_move');
    }
    
    public function zones()
    {
        return $this->belongsToMany(HitZone::class, 'character_move_hit_zone', 'character_move_id', 'hit_zone_id')->withPivot('order_in_zone_list');
    }

    public function conditions()
    {
        return $this->belongsToMany(CharacterMoveCondition::class, 'character_move_character_move_condition', 'character_move_id', 'character_move_condition');
    }

    public function properties()
    {
        // return $this->belongsToMany(CharacterMoveProperty::class, 'character_move_character_move_property', 'character_move_id', 'character_move_property_id');
    }

    public function followsUp()
    {
        return $this->belongsToMany(CharacterMove::class, 'character_move_follow_ups', 'follow_up_move_id', 'character_move_id');
    }

    public function followUps()
    {
        return $this->belongsToMany(CharacterMove::class, 'character_move_follow_ups', 'character_move_id', 'follow_up_move_id');
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
