<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cari_kata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cerita_id')->constrained('cerita')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // game-specific columns
            $table->integer('score')->default(0)->comment('Skor untuk sesi cari kata');
            $table->integer('total_words')->default(0)->comment('Jumlah kata yang harus ditemukan');
            $table->integer('found_words')->default(0)->comment('Jumlah kata yang ditemukan');
            $table->integer('attempts')->default(1)->comment('Jumlah percobaan pemain');
            $table->integer('time_seconds')->nullable()->comment('Waktu yang digunakan (detik)');

            // grid & solution can be stored as JSON so the same puzzle can be recreated
            $table->json('grid')->nullable()->comment('Representasi grid huruf');
            $table->json('solution')->nullable()->comment('Daftar kata dan koordinatnya');
            $table->json('state')->nullable()->comment('State permainan (kata yang telah ditemukan)');

            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cari_kata');
    }
};
