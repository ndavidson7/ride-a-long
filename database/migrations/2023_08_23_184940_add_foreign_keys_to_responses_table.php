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
        Schema::table('responses', function (Blueprint $table) {
            $table->foreign(['user_id'], 'responses_ibfk_2')->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['ride_id'], 'responses_ibfk_1')->references(['id'])->on('rides')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('responses', function (Blueprint $table) {
            $table->dropForeign('responses_ibfk_2');
            $table->dropForeign('responses_ibfk_1');
        });
    }
};
