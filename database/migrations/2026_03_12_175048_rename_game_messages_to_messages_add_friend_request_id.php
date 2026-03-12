<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('game_messages', 'messages');

        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('game_id')->nullable()->change();
            $table->foreignId('friend_request_id')->nullable()->constrained()->cascadeOnDelete()->after('game_id');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['friend_request_id']);
            $table->dropColumn('friend_request_id');
            $table->foreignId('game_id')->nullable(false)->change();
        });

        Schema::rename('messages', 'game_messages');
    }
};
