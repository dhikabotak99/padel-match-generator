<?php

namespace App\Services;

use App\Models\PadelMatch;
use App\Models\Player;
use App\Models\Game;
use App\Models\Round;

class GameLogicService
{
    public function generateRound(PadelMatch $match)
    {
        $roundNumber = $match->rounds()->count() + 1;
        $round = $match->rounds()->create([
            'round_number' => $roundNumber
        ]);

        $players = $match->players;
        $playerCount = $players->count();

        // Basic check for multiple of 4 for MVP simplicity
        // In real app, we'd handle byes or uneven courts
        // if ($playerCount % 4 !== 0) {
        //     // For now, let's just take the first N - (N%4) players
        //     // Or throw error? User requested "Some Player", so we should handle it.
        //     // Let's implement a bye system later. For now, strict 4s.
        // }

        $courts = $match->courts;

        if ($match->type === 'americano') {
            $this->generateAmericanoGames($round, $players, $courts);
        } elseif ($match->type === 'mexicano') {
            $this->generateMexicanoGames($round, $players, $courts);
        }

        return $round;
    }

    protected function generateAmericanoGames(Round $round, $players, $courts)
    {
        // Simple random mix for MVP, trying to avoid repeat partners if possible
        // Ideally: Round Robin logic.
        // For N=4: Fixed schedule.
        // For N=8: Split into 2 courts, mix.
        
        $shuffled = $players->shuffle();
        $chunks = $shuffled->chunk(4);
        $courtIndex = 0;

        foreach ($chunks as $chunk) {
            if ($chunk->count() < 4) continue; // Skip if not enough for a game (sit out)

            $p = $chunk->values();
            // Create game: (0,1) vs (2,3) - Simplest approach
            // TODO: Improve to track history and optimize pairings
            
            $court = $courts->count() > 0 ? $courts[$courtIndex % $courts->count()] : null;
            $courtIndex++;

            Game::create([
                'round_id' => $round->id,
                'court_id' => $court ? $court->id : null,
                'team_a_player_1_id' => $p[0]->id,
                'team_a_player_2_id' => $p[1]->id,
                'team_b_player_1_id' => $p[2]->id,
                'team_b_player_2_id' => $p[3]->id,
            ]);
        }
    }

    protected function generateMexicanoGames(Round $round, $players, $courts)
    {
        // Mexicano: Sort by score, then 1&4 vs 2&3
        // Calculate scores
        $matchId = $round->padel_match_id;
        
        // Helper to get player scores in this match
        $playerScores = $players->map(function ($player) use ($matchId) {
            // Very inefficient query loop, optimize later
            // Sum scores from games in this match
            // For MVP: Just strict logic
            $score = 0;
            // TODO: perform proper score calculation query
            return ['player' => $player, 'score' => rand(0, 100)]; // Mock score for round 1/testing
        });

        // If Round 1, random or initial rank (level)
        if ($round->round_number === 1) {
             $sortedPlayers = $players->has('level') ? $players->sortByDesc('level') : $players->shuffle();
        } else {
             // Sort by actual score
             // For now, mock shuffle for "leaderboard" simulation until we have real scores
             $sortedPlayers = $players->shuffle(); 
        }

        $chunks = $sortedPlayers->chunk(4);
        $courtIndex = 0;

        foreach ($chunks as $chunk) {
             if ($chunk->count() < 4) continue;
             $p = $chunk->values();
             
             // Mexicano pairing: (1, 4) vs (2, 3)
             // Indices: 0, 3 vs 1, 2
             
             $court = $courts->count() > 0 ? $courts[$courtIndex % $courts->count()] : null;
             $courtIndex++;

            Game::create([
                'round_id' => $round->id,
                'court_id' => $court ? $court->id : null,
                'team_a_player_1_id' => $p[0]->id,
                'team_a_player_2_id' => $p[3]->id,
                'team_b_player_1_id' => $p[1]->id,
                'team_b_player_2_id' => $p[2]->id,
            ]);
        }
    }
}
