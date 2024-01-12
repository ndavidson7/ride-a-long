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
        Schema::table('new_ride_alerts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('origin_id')->references('id')->on('addresses');
            $table->foreign('destination_id')->references('id')->on('addresses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('new_ride_alerts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['origin_id']);
            $table->dropForeign(['destination_id']);
        });
    }
};
