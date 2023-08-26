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
        Schema::create('requests', function (Blueprint $table) {
            $table->unsignedInteger('ride_id');
            $table->unsignedInteger('user_id')->index('user_id');
            $table->unsignedInteger('pickup_address_id')->nullable()->index('pickup_address_id');
            $table->unsignedInteger('dropoff_address_id')->nullable()->index('dropoff_address_id');

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
        Schema::dropIfExists('requests');
    }
};
