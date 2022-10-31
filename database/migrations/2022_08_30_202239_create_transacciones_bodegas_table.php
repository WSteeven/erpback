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
        Schema::create('transacciones_bodega', function (Blueprint $table) {
            $table->id();
            $table->string('justificacion')->nullable();
            $table->string('comprobante')->nullable();
            $table->date('fecha_limite')->nullable();
            $table->unsignedBigInteger('solicitante_id');
            $table->unsignedBigInteger('subtipo_id');
            $table->unsignedBigInteger('tarea_id')->nullable();
            $table->unsignedBigInteger('subtarea_id')->nullable();
            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->unsignedBigInteger('per_autoriza_id')->nullable();
            $table->unsignedBigInteger('per_atiende_id')->nullable();
            $table->timestamps();

            $table->foreign('tarea_id')->references('id')->on('subtareas');
            $table->foreign('subtarea_id')->references('id')->on('subtareas');
            $table->foreign('solicitante_id')->references('id')->on('empleados');
            $table->foreign('per_autoriza_id')->references('id')->on('empleados');
            $table->foreign('per_atiende_id')->references('id')->on('empleados');
            $table->foreign('subtipo_id')->references('id')->on('subtipos_transacciones');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transacciones_bodega');
    }
};
