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
        Schema::table('cerita', function (Blueprint $table) {
            $table->boolean('video_processing')->default(false)->after('video');
            $table->timestamp('video_processed_at')->nullable()->after('video_processing');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cerita', function (Blueprint $table) {
            $table->dropColumn(['video_processing', 'video_processed_at']);
        });
    }
};
