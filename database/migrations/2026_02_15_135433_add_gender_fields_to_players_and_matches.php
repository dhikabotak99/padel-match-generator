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
        // players table already has gender column (string)
        // Schema::table('players', function (Blueprint $table) {
        //     $table->enum('gender', ['male', 'female'])->nullable()->after('level');
        // });

        Schema::table('padel_matches', function (Blueprint $table) {
            $table->enum('gender_type', ['open', 'mixed'])->default('open')->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::table('players', function (Blueprint $table) {
        //     $table->dropColumn('gender');
        // });

        Schema::table('padel_matches', function (Blueprint $table) {
            $table->dropColumn('gender_type');
        });
    }
};
