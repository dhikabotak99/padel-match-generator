<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    public function updateScore(Request $request, Game $game)
    {
        $validated = $request->validate([
            'score_team_a' => 'required|integer|min:0',
            'score_team_b' => 'required|integer|min:0',
        ]);

        $game->update($validated);

        return response()->json($game, 200);
    }
}
