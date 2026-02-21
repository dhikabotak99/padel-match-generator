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

    public function index()
    {
        return response()->json(auth()->user()->matches()->withCount('players')->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string',
            'type' => 'required|in:americano,mexicano',
            'gender_type' => 'nullable|in:open,mixed',
            'scoring_type' => 'required|in:21,tennis',
            'players' => 'required|array|min:4',
            'courts_count' => 'required|integer|min:1',
        ]);

        $match = auth()->user()->matches()->create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'gender_type' => $validated['gender_type'] ?? 'open',
            'scoring_type' => $validated['scoring_type'],
            'status' => 'pending',
        ]);

        // Create Courts
        $courtsCount = $validated['courts_count'];
        for ($i = 1; $i <= $courtsCount; $i++) {
            $match->courts()->create([
                'name' => "Court $i"
            ]);
        }

        $playerIds = [];
        foreach ($validated['players'] as $playerData) {
            if (is_array($playerData)) {
                $name = $playerData['name'];
                $gender = $playerData['gender'] ?? null;
            } else {
                $name = $playerData;
                $gender = null;
            }

            $player = Player::firstOrCreate(['name' => $name]);
            
            if ($gender) {
                $player->update(['gender' => $gender]);
            }
            
            $playerIds[] = $player->id;
        }

        $match->players()->sync($playerIds);

        return response()->json($match->load('players', 'courts'), 201);
    }

    public function show(PadelMatch $padelMatch)
    {
        return response()->json($padelMatch->load(['players', 'courts', 'rounds.games.court', 'rounds.games.teamAPlayer1', 'rounds.games.teamAPlayer2', 'rounds.games.teamBPlayer1', 'rounds.games.teamBPlayer2']));
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
