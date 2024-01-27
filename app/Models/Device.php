<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function deviceButtons()
    {
        return $this->hasMany(DeviceButton::class);
    }

    // public function gameMappings()
    // {
    //     return $this->hasMany(GameMapping::class);
    // }

}
