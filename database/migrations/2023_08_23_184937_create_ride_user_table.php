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
        Schema::create('ride_user', function (Blueprint $table) {
            $table->unsignedInteger('ride_id');
            $table->unsignedInteger('user_id')->index('user_id');
            $table->unsignedInteger('pickup_waypoint_id')->nullable()->index('pickup_waypoint_id');
            $table->unsignedInteger('dropoff_waypoint_id')->nullable()->index('dropoff_waypoint_id');

            $table->primary(['ride_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riders');
    }
};
