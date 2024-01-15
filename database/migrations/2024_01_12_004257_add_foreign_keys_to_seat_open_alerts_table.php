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
        Schema::table('seat_open_alerts', function (Blueprint $table) {
            $table->foreign('ride_id')->references('id')->on('rides');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seat_open_alerts', function (Blueprint $table) {
            $table->dropForeign(['ride_id']);
            $table->dropForeign(['user_id']);
        });
    }
};
