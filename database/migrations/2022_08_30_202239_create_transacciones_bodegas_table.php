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
            $table->text('justificacion')->nullable();
            $table->string('comprobante')->nullable();
            $table->date('fecha_limite')->nullable();
            $table->unsignedBigInteger('solicitante_id');
            $table->unsignedBigInteger('motivo_id')->nullable();
            $table->unsignedBigInteger('tarea_id')->nullable();
            $table->unsignedBigInteger('tipo_id')->nullable();
            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->unsignedBigInteger('per_autoriza_id')->nullable();
            $table->unsignedBigInteger('per_atiende_id')->nullable();
            $table->unsignedBigInteger('per_retira_id')->nullable();
            $table->timestamps();

            $table->foreign('solicitante_id')->references('id')->on('empleados');
            $table->foreign('motivo_id')->references('id')->on('motivos');
            $table->foreign('tarea_id')->references('id')->on('subtareas');
            $table->foreign('tipo_id')->references('id')->on('tipos_transacciones');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('per_autoriza_id')->references('id')->on('empleados');
            $table->foreign('per_atiende_id')->references('id')->on('empleados');
            $table->foreign('per_retira_id')->references('id')->on('empleados');
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
