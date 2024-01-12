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
        Schema::create('new_ride_alerts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('origin_id');
            $table->unsignedSmallInteger('origin_radius')->default(0);
            $table->unsignedInteger('destination_id');
            $table->unsignedSmallInteger('destination_radius')->default(0);
            $table->boolean('strict')->default(false); // if true, require both origin and destination to pass radius check
            $table->date('start_date'); //->nullable();
            $table->date('end_date'); //->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_ride_alerts');
    }
};
