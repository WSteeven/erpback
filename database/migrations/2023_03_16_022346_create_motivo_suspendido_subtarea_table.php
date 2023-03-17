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
        Schema::create('motivo_suspendido_subtarea', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('motivo_suspendido_id');
            $table->foreign('motivo_suspendido_id')->references('id')->on('motivos_suspendidos');

            $table->unsignedBigInteger('subtarea_id');
            $table->foreign('subtarea_id')->references('id')->on('subtareas');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motivo_suspendido_subtarea');
    }
};
