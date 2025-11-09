<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Büyük media_data değerlerini temizle (base64 fotoğraf verileri)
        DB::table('posts')
            ->whereNotNull('media_data')
            ->whereRaw('LENGTH(media_data) > 1000')
            ->update(['media_data' => null]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Geri alınamaz - veri kaybı
    }
};