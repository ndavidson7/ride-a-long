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
        Schema::create('messages', function (Blueprint $table) {
            $table->unsignedInteger('sender_id');
            $table->unsignedInteger('recipient_id')->index('recipient_id');
            $table->timestamp('time_sent')->useCurrent();
            $table->string('message');

            $table->primary(['sender_id', 'recipient_id', 'time_sent']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
