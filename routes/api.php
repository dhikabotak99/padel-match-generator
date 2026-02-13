<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\GameController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('matches')->group(function () {
    Route::post('/', [MatchController::class, 'store']);
    Route::get('/{padelMatch}', [MatchController::class, 'show']);
    Route::post('/{padelMatch}/start', [MatchController::class, 'startMatch']);
    Route::post('/{padelMatch}/rounds', [MatchController::class, 'nextRound']);
});

Route::prefix('games')->group(function () {
    Route::post('/{game}/score', [GameController::class, 'updateScore']);
});
