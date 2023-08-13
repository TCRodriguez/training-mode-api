<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function characterMoves()
    {
        return $this->morphedByMany(CharacterMove::class, 'taggable');
    }

    public function characterCombos()
    {
        return $this->morphedByMany(CharacterCombo::class, 'taggable');
    }

    public function notes()
    {
        return $this->morphedByMany(Note::class, 'taggable');
    }
}
