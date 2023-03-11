<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectionalInput extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function icons()
    {
        return $this->hasMany(DirectionalInputIcon::class);
    }

    public function notations()
    {
        return $this->belongsToMany(GameNotation::class, 'directional_input_game_notation', 'directional_input_id', 'game_notation_id');
    }
}
