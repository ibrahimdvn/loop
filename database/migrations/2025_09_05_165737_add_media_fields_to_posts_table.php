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
            $table->string('media_type')->nullable()->after('content'); // 'image', 'gif', 'location'
            $table->string('media_url')->nullable()->after('media_type'); // URL or path
            $table->text('media_data')->nullable()->after('media_url'); // JSON data for location, GIF info etc.
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
            $table->dropColumn(['media_type', 'media_url', 'media_data']);
        });
    }
};
