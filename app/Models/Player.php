<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = ['name', 'gender', 'level'];

    public function matches()
    {
        return $this->belongsToMany(PadelMatch::class, 'padel_match_player');
    }
}
