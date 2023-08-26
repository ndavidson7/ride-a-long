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
        Schema::create('rides', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('driver_id')->index('driver_id');
            $table->timestamp('start_time')->nullable();
            $table->unsignedInteger('origin_address_id')->index('origin_address_id');
            $table->unsignedInteger('destination_address_id')->index('destination_address_id');
            $table->unsignedInteger('seats_total');
            $table->string('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rides');
    }
};
