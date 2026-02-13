<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PadelMatch;
use App\Models\Player;

class MatchController extends Controller
{
    protected $gameLogic;

    public function __construct(\App\Services\GameLogicService $gameLogic)
    {
        $this->gameLogic = $gameLogic;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string',
            'type' => 'required|in:americano,mexicano',
            'scoring_type' => 'required|in:21,tennis',
            'players' => 'required|array|min:4',
            'players.*' => 'required|string',
        ]);

        $match = PadelMatch::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'scoring_type' => $validated['scoring_type'],
            'status' => 'pending',
        ]);

        $playerIds = [];
        foreach ($validated['players'] as $playerName) {
            $player = Player::firstOrCreate(['name' => $playerName]);
            $playerIds[] = $player->id;
        }

        $match->players()->sync($playerIds);

        return response()->json($match->load('players'), 201);
    }

    public function show(PadelMatch $padelMatch)
    {
        return response()->json($padelMatch->load(['players', 'rounds.games.court', 'rounds.games.teamAPlayer1', 'rounds.games.teamAPlayer2', 'rounds.games.teamBPlayer1', 'rounds.games.teamBPlayer2']));
    }

    public function startMatch(PadelMatch $padelMatch)
    {
        if ($padelMatch->rounds()->count() > 0) {
            return response()->json(['message' => 'Match already started'], 400);
        }

        $padelMatch->update(['status' => 'active']);
        $round = $this->gameLogic->generateRound($padelMatch);

        return response()->json($round->load('games'), 200);
    }

    public function nextRound(PadelMatch $padelMatch)
    {
        // Check if previous rounds are finished
        // For MVP, just generate next
        $round = $this->gameLogic->generateRound($padelMatch);

        return response()->json($round->load('games'), 200);
    }
}
