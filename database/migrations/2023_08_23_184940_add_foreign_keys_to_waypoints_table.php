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
        Schema::table('waypoints', function (Blueprint $table) {
            $table->foreign(['address_id'], 'waypoints_ibfk_2')->references(['id'])->on('addresses')->onDelete('CASCADE');
            $table->foreign(['ride_id'], 'waypoints_ibfk_1')->references(['id'])->on('rides')->onDelete('CASCADE');
            $table->foreign(['before'], 'waypoints_ibfk_3')->references(['id'])->on('waypoints')->onDelete('CASCADE');
            $table->foreign(['after'], 'waypoints_ibfk_4')->references(['id'])->on('waypoints')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('waypoints', function (Blueprint $table) {
            $table->dropForeign('waypoints_ibfk_1');
            $table->dropForeign('waypoints_ibfk_2');
            $table->dropForeign('waypoints_ibfk_3');
            $table->dropForeign('waypoints_ibfk_4');
        });
    }
};
