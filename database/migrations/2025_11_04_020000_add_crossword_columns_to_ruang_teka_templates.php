<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
    // Add crossword columns to the ruang_teka template table (guard if table exists)
    if (!Schema::hasTable('ruang_teka')) return;
    Schema::table('ruang_teka', function (Blueprint $table) {
            // grid dimensions for crossword-style templates
            if (!Schema::hasColumn('ruang_teka', 'grid_rows')) $table->integer('grid_rows')->nullable()->after('difficulty');
            if (!Schema::hasColumn('ruang_teka', 'grid_cols')) $table->integer('grid_cols')->nullable()->after('grid_rows');

            // optional: explicit grid representation (2D array of chars/null) and clues
            if (!Schema::hasColumn('ruang_teka', 'grid')) $table->json('grid')->nullable()->after('content')->comment('2D array of cell letters or null for black cells');
            if (!Schema::hasColumn('ruang_teka', 'clues')) $table->json('clues')->nullable()->after('grid')->comment('Clues and placements (across/down)');
        });
    }

    public function down()
    {
        Schema::table('ruang_teka', function (Blueprint $table) {
            $table->dropColumn(['grid_rows', 'grid_cols', 'grid', 'clues']);
        });
    }
};
