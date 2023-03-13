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
        Schema::create('pausas_subtareas', function (Blueprint $table) {
            $table->id();
            $table->string('fecha_hora_pausa');
            $table->string('fecha_hora_retorno')->nullable();

            $table->unsignedBigInteger('motivo_pausa_id');
            $table->foreign('motivo_pausa_id')->references('id')->on('motivos_pausas');

            $table->unsignedBigInteger('subtarea_id');
            $table->foreign('subtarea_id')->references('id')->on('subtareas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pausas_subtareas');
    }
};
