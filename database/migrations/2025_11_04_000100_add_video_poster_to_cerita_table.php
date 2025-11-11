<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cerita', function (Blueprint $table) {
            $table->string('video_poster')->nullable()->after('video_processed_at');
        });
    }

    public function down()
    {
        Schema::table('cerita', function (Blueprint $table) {
            $table->dropColumn('video_poster');
        });
    }
};
