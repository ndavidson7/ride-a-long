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
        Schema::table('rides', function (Blueprint $table) {
            $table->foreign(['origin_id'], 'rides_ibfk_2')->references(['id'])->on('addresses');
            $table->foreign(['driver_id'], 'rides_ibfk_1')->references(['id'])->on('drivers')->onDelete('CASCADE');
            $table->foreign(['destination_id'], 'rides_ibfk_3')->references(['id'])->on('addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropForeign('rides_ibfk_2');
            $table->dropForeign('rides_ibfk_1');
            $table->dropForeign('rides_ibfk_3');
        });
    }
};
