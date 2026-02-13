<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PadelMatch extends Model
{
    protected $fillable = ['name', 'type', 'scoring_type', 'status'];

    public function rounds()
    {
        return $this->hasMany(Round::class);
    }

    public function players()
    {
        return $this->belongsToMany(Player::class, 'padel_match_player');
    }

    public function courts()
    {
        return $this->hasMany(Court::class);
    }
}
