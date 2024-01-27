<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameDeviceDirectionalInputMapping extends Model
{
    use HasFactory;

    protected $table = 'device_button_directional_input';

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function deviceButton()
    {
        return $this->belongsTo(DeviceButton::class, 'device_button_id');
    }
}
