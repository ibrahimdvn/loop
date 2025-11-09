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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Bildirim alan kullanıcı
            $table->foreignId('from_user_id')->nullable()->constrained('users')->onDelete('cascade'); // Bildirim gönderen kullanıcı
            $table->foreignId('post_id')->nullable()->constrained()->onDelete('cascade'); // İlgili gönderi
            $table->string('type'); // like, comment, follow, etc.
            $table->text('message'); // Bildirim mesajı
            $table->boolean('read')->default(false); // Okundu mu?
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
        Schema::dropIfExists('notifications');
    }
};
