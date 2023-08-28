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
            $table->text('message')->nullable();
            $table->boolean('response')->nullable();
            // Updated_at will show when the response was given:
            // users will not be allowed to edit their own requests,
            // so they will only be updated by the drivers' responses.
            $table->timestamps();

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
