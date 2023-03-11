<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectionalInputIcon extends Model
{
    use HasFactory;

    public function directionalInput()
    {
        return $this->belongsTo(DirectionalInput::class, 'directional_input_id');
    }
}
