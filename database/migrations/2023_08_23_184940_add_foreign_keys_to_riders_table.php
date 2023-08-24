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
        Schema::table('riders', function (Blueprint $table) {
            $table->foreign(['user_id'], 'ride_riders_ibfk_2')->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['ride_id'], 'ride_riders_ibfk_1')->references(['id'])->on('rides')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('riders', function (Blueprint $table) {
            $table->dropForeign('ride_riders_ibfk_2');
            $table->dropForeign('ride_riders_ibfk_1');
        });
    }
};
