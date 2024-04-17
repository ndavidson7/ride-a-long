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
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('street_address');
            $table->string('city');
            $table->unsignedTinyInteger('state_id');
            $table->string('postal_code', 10);
            $table->unsignedTinyInteger('country_id');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->timestamp('created_at')->nullable();

            $table->unique(['street_address', 'city', 'state_id', 'country_id'], 'unique_address');
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('addresses');
    }
};
