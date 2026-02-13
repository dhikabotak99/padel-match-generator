<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('round_id')->constrained()->onDelete('cascade');
            $table->foreignId('court_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('team_a_player_1_id')->constrained('players')->onDelete('cascade');
            $table->foreignId('team_a_player_2_id')->constrained('players')->onDelete('cascade');
            $table->foreignId('team_b_player_1_id')->constrained('players')->onDelete('cascade');
            $table->foreignId('team_b_player_2_id')->constrained('players')->onDelete('cascade');
            $table->integer('score_team_a')->default(0);
            $table->integer('score_team_b')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
