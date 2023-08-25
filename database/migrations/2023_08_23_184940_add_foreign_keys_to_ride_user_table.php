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
        Schema::table('ride_user', function (Blueprint $table) {
            /*
                TODO: Think about implications of deleting a waypoint.
                Should drivers be able to delete waypoints? If so, what happens to the riders?
            */
            $table->foreign(['dropoff_waypoint_id'], 'ride_riders_ibfk_4')->references(['id'])->on('waypoints')->onDelete('SET NULL');
            $table->foreign(['pickup_waypoint_id'], 'ride_riders_ibfk_3')->references(['id'])->on('waypoints')->onDelete('SET NULL');
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
            $table->dropForeign('ride_riders_ibfk_4');
            $table->dropForeign('ride_riders_ibfk_3');
            $table->dropForeign('ride_riders_ibfk_2');
            $table->dropForeign('ride_riders_ibfk_1');
        });
    }
};
