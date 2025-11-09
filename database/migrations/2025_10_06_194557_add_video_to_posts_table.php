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
        Schema::table('posts', function (Blueprint $table) {
            $table->string('video')->nullable()->after('image');
            $table->string('video_url')->nullable()->after('video');
            $table->string('video_thumbnail')->nullable()->after('video_url');
            $table->integer('video_duration')->nullable()->after('video_thumbnail');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn(['video', 'video_url', 'video_thumbnail', 'video_duration']);
        });
    }
};
