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
        Schema::create('states', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 50);
            $table->char('code', 3);

            $table->unique('name');
            $table->unique('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
