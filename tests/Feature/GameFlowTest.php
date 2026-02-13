<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\PadelMatch;
use App\Models\Player;

class GameFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_americano_flow()
    {
        // 1. Create Match
        $response = $this->postJson('/api/matches', [
            'name' => 'Test Americano',
            'type' => 'americano',
            'scoring_type' => '21',
            'players' => ['P1', 'P2', 'P3', 'P4']
        ]);

        $response->assertStatus(201);
        $matchId = $response->json('id');
        
        $this->assertDatabaseHas('padel_matches', ['id' => $matchId, 'type' => 'americano']);
        $this->assertCount(4, Player::all());

        // 2. Start Match
        $response = $this->postJson("/api/matches/{$matchId}/start");
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('rounds', ['padel_match_id' => $matchId, 'round_number' => 1]);
        $this->assertDatabaseCount('games', 1);

        // 3. Score Game
        $game = \App\Models\Game::first();
        $response = $this->postJson("/api/games/{$game->id}/score", [
            'score_team_a' => 21,
            'score_team_b' => 15
        ]);
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('games', ['id' => $game->id, 'score_team_a' => 21]);

        // 4. Next Round
        $response = $this->postJson("/api/matches/{$matchId}/rounds");
        $response->assertStatus(200);
        
        $this->assertDatabaseHas('rounds', ['padel_match_id' => $matchId, 'round_number' => 2]);
        $this->assertDatabaseCount('games', 2); // 1 from R1, 1 from R2
    }

    public function test_mexicano_flow()
    {
         // 1. Create Match
         $response = $this->postJson('/api/matches', [
            'name' => 'Test Mexicano',
            'type' => 'mexicano',
            'scoring_type' => '21',
            'players' => ['P1', 'P2', 'P3', 'P4']
        ]);

        $matchId = $response->json('id');

        // 2. Start Match
        $this->postJson("/api/matches/{$matchId}/start");
        
        // 3. Score
        $game = \App\Models\Game::whereHas('round', function($q) { $q->where('round_number', 1); })->first();
        $this->postJson("/api/games/{$game->id}/score", ['score_team_a' => 21, 'score_team_b' => 10]);

        // 4. Next Round
        $this->postJson("/api/matches/{$matchId}/rounds");
        
        // Check finding 2nd round
        $this->assertDatabaseHas('rounds', ['padel_match_id' => $matchId, 'round_number' => 2]);
    }
}
