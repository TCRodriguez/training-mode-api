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

    public function deviceButtonMappings()
    {
        return $this->belongsToMany(DeviceButton::class, 'device_button_directional_input', 'directional_input_id', 'device_button_id');
    }
}
