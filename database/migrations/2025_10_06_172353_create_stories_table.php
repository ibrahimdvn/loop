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
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('media_type'); // image, video
            $table->string('media_url');
            $table->json('media_data')->nullable(); // For processed media
            $table->text('caption')->nullable();
            $table->json('hashtags')->nullable();
            $table->json('mentions')->nullable(); // @mentions
            $table->string('background_color')->nullable(); // For text stories
            $table->string('text_color')->nullable(); // For text stories
            $table->string('font_family')->nullable(); // For text stories
            $table->integer('font_size')->default(24); // For text stories
            $table->boolean('is_highlight')->default(false); // Story highlights
            $table->string('highlight_title')->nullable(); // For highlights
            $table->string('highlight_cover')->nullable(); // Cover image for highlights
            $table->timestamp('expires_at'); // 24 hours from creation
            $table->timestamps();
            
            $table->index(['user_id', 'expires_at']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stories');
    }
};
