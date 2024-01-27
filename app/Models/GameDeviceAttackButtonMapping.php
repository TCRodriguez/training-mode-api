<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GameDeviceAttackButtonMapping extends Model
{
    use HasFactory;


    // protected $guarded = [];
    protected $table = 'attack_button_device_button';

    public function game()
    {
        return $this->belongsTo(Game::class, 'game_id');
    }

    public function deviceButton()
    {
        return $this->belongsTo(DeviceButton::class, 'device_button_id');
    }

    public function attackButton()
    {
        return $this->belongsTo(AttackButton::class, 'attack_button_id');
    }
}
