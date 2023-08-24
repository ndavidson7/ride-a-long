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
        Schema::table('drivers', function (Blueprint $table) {
            $table->foreign(['car_license_plate'], 'drivers_ibfk_2')->references(['license_plate'])->on('cars')->onDelete('CASCADE');
            $table->foreign(['user_id'], 'drivers_ibfk_1')->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('drivers', function (Blueprint $table) {
            $table->dropForeign('drivers_ibfk_2');
            $table->dropForeign('drivers_ibfk_1');
        });
    }
};
