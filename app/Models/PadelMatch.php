<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PadelMatch extends Model
{
    protected $fillable = ['name', 'type', 'scoring_type', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

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
