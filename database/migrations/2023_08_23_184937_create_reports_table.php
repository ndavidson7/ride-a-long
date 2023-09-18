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
        Schema::create('reports', function (Blueprint $table) {
            $table->unsignedInteger('reporter_id');
            $table->unsignedInteger('reportee_id')->index('reportee_id');
            $table->string('reason', 63);
            $table->string('info')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->primary(['reporter_id', 'reportee_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
};
