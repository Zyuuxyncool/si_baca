<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // ensure cari_kata keeps template_id (if templates exist)
        if (Schema::hasTable('cari_kata')) {
            Schema::table('cari_kata', function (Blueprint $table) {
                if (!Schema::hasColumn('cari_kata', 'template_id')) {
                    $table->foreignId('template_id')->nullable()->after('id');
                    if (Schema::hasTable('cari_kata_templates')) {
                        $table->foreign('template_id')->references('id')->on('cari_kata_templates')->onDelete('set null');
                    }
                }
            });
        }

        if (Schema::hasTable('cari_kata')) {
            Schema::table('cari_kata', function (Blueprint $table) {
                if (!Schema::hasColumn('cari_kata', 'template_id')) {
                    $table->foreignId('template_id')->nullable()->after('id');
                    if (Schema::hasTable('cari_kata_templates')) {
                        $table->foreign('template_id')->references('id')->on('cari_kata_templates')->onDelete('set null');
                    }
                }
            });
        }
    }

    public function down()
    {
        Schema::table('cari_kata', function (Blueprint $table) {
            if (Schema::hasColumn('cari_kata', 'template_id')) {
                $table->dropForeign(['template_id']);
                $table->dropColumn('template_id');
            }
        });
    }
};
