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
        Schema::table('requests', function (Blueprint $table) {
            $table->foreign(['user_id'], 'requests_ibfk_2')->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['dropoff_addr'], 'requests_ibfk_4')->references(['address'])->on('coordinates');
            $table->foreign(['ride_id'], 'requests_ibfk_1')->references(['id'])->on('rides')->onDelete('CASCADE');
            $table->foreign(['pickup_addr'], 'requests_ibfk_3')->references(['address'])->on('coordinates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign('requests_ibfk_2');
            $table->dropForeign('requests_ibfk_4');
            $table->dropForeign('requests_ibfk_1');
            $table->dropForeign('requests_ibfk_3');
        });
    }
};
