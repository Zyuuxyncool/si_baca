<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cerita', function (Blueprint $table) {
            if (Schema::hasColumn('cerita', 'video_poster')) {
                $table->dropColumn('video_poster');
            }
        });
    }

    public function down()
    {
        Schema::table('cerita', function (Blueprint $table) {
            if (!Schema::hasColumn('cerita', 'video_poster')) {
                $table->string('video_poster')->nullable()->after('video_processed_at');
            }
        });
    }
};
