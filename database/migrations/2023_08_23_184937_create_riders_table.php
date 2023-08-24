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
        Schema::create('riders', function (Blueprint $table) {
            $table->unsignedInteger('ride_id');
            $table->unsignedInteger('user_id')->index('user_id');
            $table->string('pickup_addr')->nullable()->index('pickup_waypoint');
            $table->string('dropoff_addr')->nullable()->index('dropoff_waypoint');

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
