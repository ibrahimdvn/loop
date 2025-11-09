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
        Schema::table('notifications', function (Blueprint $table) {
            // Add comment_id for comment notifications
            $table->foreignId('comment_id')->nullable()->after('post_id')->constrained()->onDelete('cascade');
            
            // Add notifiable_id (the person who performed the action - replaces from_user_id)
            $table->foreignId('notifiable_id')->nullable()->after('user_id')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['comment_id']);
            $table->dropColumn('comment_id');
            $table->dropForeign(['notifiable_id']);
            $table->dropColumn('notifiable_id');
        });
    }
};
