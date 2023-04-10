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


        // return $this->belongsToMany(DirectionalInput::class, 'directional_input_game_notation', 'game_notation_id', 'directional_input_id');

        return $this->belongsToMany(DirectionalInput::class, 'character_move_directional_input', 'character_move_id', 'directional_input_id')->withPivot('order_in_move');
    }

    public function attackButtons()
    {
        // return $this->belongsToMany(AttackButton::class);
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

    // protected function inputs(): Attribute
    // {
    //     return new Attribute(
    //         get: fn () => DirectionalInput::where('direction', 'Up')->get()
    //     );
    // }
    // protected function inputs()
    // {
    //     return {
    //         $this->directionalInputs(),
    //         $this->attackButtons()
    //     };
    // }
}
