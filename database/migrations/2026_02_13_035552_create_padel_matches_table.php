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
        Schema::create('padel_matches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type'); // americano, mexicano
            $table->string('scoring_type'); // 21, tennis
            $table->string('status')->default('pending'); // pending, active, completed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('padel_matches');
    }
};
