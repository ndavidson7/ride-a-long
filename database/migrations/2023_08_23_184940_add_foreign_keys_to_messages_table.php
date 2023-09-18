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
        Schema::table('messages', function (Blueprint $table) {
            $table->foreign(['recipient_id'], 'messages_ibfk_2')->references(['id'])->on('users')->onDelete('CASCADE');
            $table->foreign(['sender_id'], 'messages_ibfk_1')->references(['id'])->on('users')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign('messages_ibfk_2');
            $table->dropForeign('messages_ibfk_1');
        });
    }
};
