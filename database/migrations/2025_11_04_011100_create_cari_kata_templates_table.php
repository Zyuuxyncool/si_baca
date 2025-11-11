<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cari_kata_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cerita_id')->constrained('cerita')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('slug')->nullable()->index();
            $table->boolean('active')->default(false);
            $table->string('difficulty')->nullable();
            $table->integer('grid_rows')->default(12);
            $table->integer('grid_cols')->default(12);
            $table->json('directions')->nullable()->comment('allowed directions');
            $table->boolean('allow_overlap')->default(true);
            $table->integer('points_default')->default(5);
            $table->text('instructions')->nullable();
            $table->string('poster')->nullable();
            $table->json('content')->nullable()->comment('word list and optional placement');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cari_kata_templates');
    }
};
