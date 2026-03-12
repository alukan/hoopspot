<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courts', function (Blueprint $table) {
            $table->enum('coverage', ['indoor', 'outdoor'])->default('outdoor')->after('description');
            $table->enum('rim_type', ['standard', 'breakaway', 'chain', 'bent'])->default('standard')->after('coverage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courts', function (Blueprint $table) {
            $table->dropColumn(['coverage', 'rim_type']);
        });
    }
};
