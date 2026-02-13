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
        
        // Fair rotation logic: Prioritize players who have played fewer games
        // 1. Get all players with their game count for this match
        // We shuffle FIRST to ensure that if players have the same game count, 
        // the order is random (tie-breaking).
        $playersWithGamesCount = $players->shuffle()->map(function ($player) use ($round) {
            $gamesPlayed = $round->match->rounds->flatMap->games->filter(function ($game) use ($player) {
                return $game->team_a_player_1_id == $player->id ||
                       $game->team_a_player_2_id == $player->id ||
                       $game->team_b_player_1_id == $player->id ||
                       $game->team_b_player_2_id == $player->id;
            })->count();
            
            return ['player' => $player, 'count' => $gamesPlayed];
        })->sortBy('count'); // Ascending: those with fewest games first

        // 2. Select the active players for this round based on capacity
        $maxGames = $courts->count() > 0 ? $courts->count() : 1;
        $needed = $maxGames * 4;
        
        $activeParticipants = $playersWithGamesCount->take($needed)->pluck('player');
        
        // 3. Shuffle ONLY the participants to mix teams, but ensure these specific N players play
        $shuffled = $activeParticipants->shuffle();
        $chunks = $shuffled->chunk(4);
        
        $gamesCreated = 0;
        $courtIndex = 0;

        foreach ($chunks as $chunk) {
            if ($chunk->count() < 4) continue; 
            if ($gamesCreated >= $maxGames) break; // Should not happen if we limited effectively above, but safe guard

            $p = $chunk->values();
            
            $court = $courts->count() > 0 ? $courts[$courtIndex % $courts->count()] : null;
            $courtIndex++;
            $gamesCreated++;

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
        $maxGames = $courts->count() > 0 ? $courts->count() : 1;
        $gamesCreated = 0;
        $courtIndex = 0;

        foreach ($chunks as $chunk) {
             if ($chunk->count() < 4) continue;
             if ($gamesCreated >= $maxGames) break;

             $p = $chunk->values();
             
             // Mexicano pairing: (1, 4) vs (2, 3)
             // Indices: 0, 3 vs 1, 2
             
             $court = $courts->count() > 0 ? $courts[$courtIndex % $courts->count()] : null;
             $courtIndex++;
             $gamesCreated++;

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
