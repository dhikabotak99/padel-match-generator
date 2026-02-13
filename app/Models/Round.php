<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Round extends Model
{
    protected $fillable = ['padel_match_id', 'round_number'];

    public function match()
    {
        return $this->belongsTo(PadelMatch::class, 'padel_match_id');
    }

    public function games()
    {
        return $this->hasMany(Game::class);
    }
}
