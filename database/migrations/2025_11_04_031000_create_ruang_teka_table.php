<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('ruang_teka')) return;

        // Create ruang_teka as the TEMPLATE table (stores crossword templates)
        Schema::create('ruang_teka', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cerita_id')->constrained('cerita')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('slug')->nullable()->index();
            $table->boolean('active')->default(false);
            $table->string('difficulty')->nullable();
            $table->integer('time_limit')->nullable()->comment('default time limit per question in seconds');
            $table->integer('points_default')->default(10);
            $table->text('instructions')->nullable();
            $table->string('poster')->nullable();
            $table->json('content')->nullable()->comment('full template content (questions, options)');
            $table->json('meta')->nullable();

            // crossword-specific columns
            $table->integer('grid_rows')->nullable();
            $table->integer('grid_cols')->nullable();
            $table->json('grid')->nullable()->comment('2D array of cell letters or null for black cells');
            $table->json('clues')->nullable()->comment('Clues and placements (across/down)');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ruang_teka');
    }
};
