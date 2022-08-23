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
            $table->date('fecha_limite')->required();
            //$table->unsignedBigInteger('estado_id');
            //$table->unsignedBigInteger('autorizacion_id');
            $table->unsignedBigInteger('solicitante_id')->nullable();
            $table->unsignedBigInteger('tipo_id')->nullable();
            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->unsignedBigInteger('per_autoriza_id')->nullable();
            $table->unsignedBigInteger('per_entrega_id')->nullable();
            $table->string('lugar_destino')->nullable();
            $table->timestamps();

            $table->foreign('solicitante_id')->references('id')->on('users');
            $table->foreign('per_autoriza_id')->references('id')->on('users');
            $table->foreign('per_entrega_id')->references('id')->on('users');
            //$table->foreign('autorizacion_id')->references('id')->on('autorizaciones');
            //$table->foreign('estado_id')->references('id')->on('estados_transacciones_bodega');
            $table->foreign('tipo_id')->references('id')->on('tipo_de_transacciones');
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
