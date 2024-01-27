<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceButton extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    public function attackButtons()
    {
        return $this->belongsToMany(AttackButton::class, 'attack_button_device_button', 'device_button_id', 'attack_button_id');
    }

    public function gameDeviceAttackButtonMappings()
    {
        return $this->hasMany(GameDeviceAttackButtonMapping::class);
    }
}
