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
        Schema::create('waypoints', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('ride_id');
            $table->unsignedInteger('address_id');
            $table->tinyInteger('order');
            $table->unsignedInteger('before')->nullable();
            $table->unsignedInteger('after')->nullable();

            $table->unique(['ride_id', 'address_id']);
            $table->index(['ride_id', 'order'], 'ordering');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('waypoints');
    }
};
