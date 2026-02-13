<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    protected $fillable = ['name', 'padel_match_id'];

    public function padelMatch()
    {
        return $this->belongsTo(PadelMatch::class);
    }
}
