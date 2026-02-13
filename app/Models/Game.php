<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'round_id',
        'court_id',
        'team_a_player_1_id',
        'team_a_player_2_id',
        'team_b_player_1_id',
        'team_b_player_2_id',
        'score_team_a',
        'score_team_b'
    ];

    public function round()
    {
        return $this->belongsTo(Round::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function teamAPlayer1()
    {
        return $this->belongsTo(Player::class, 'team_a_player_1_id');
    }

    public function teamAPlayer2()
    {
        return $this->belongsTo(Player::class, 'team_a_player_2_id');
    }

    public function teamBPlayer1()
    {
        return $this->belongsTo(Player::class, 'team_b_player_1_id');
    }

    public function teamBPlayer2()
    {
        return $this->belongsTo(Player::class, 'team_b_player_2_id');
    }
}
