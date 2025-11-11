<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ruang_teka_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('grid_state')->nullable();
            $table->json('answers')->nullable();
            $table->integer('score')->default(0);
            $table->string('state')->default('playing')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ruang_teka_sessions');
    }
};
