<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('game_scores', function (Blueprint $table) {
            $table->id();
            // per-request: do not reference game_id here; scores are stored per cerita and user
            $table->unsignedBigInteger('cerita_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('game_template_id')->nullable()->index();
            $table->unsignedBigInteger('game_template_id2')->nullable();
            $table->integer('score')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('game_scores');
    }
};
