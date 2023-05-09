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
        Schema::create('rol_pago', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->double('salario');
            $table->integer('dias');
            $table->double('sueldo');
            $table->double('decimo_tercero');
            $table->double('decimo_cuarto');
            $table->double('fondos_reserva');
            $table->double('alimentacion');
            $table->double('horas_extras');
            $table->double('total_ingreso');
            $table->double('comisiones');
            $table->double('iess');
            $table->double('anticipo');
            $table->double('prestamo_quirorafario');
            $table->double('prestamo_hipotecario');
            $table->double('extension_conyugal');
            $table->double('prestamo_empresarial');
            $table->double('sancion_pecuniaria');
            $table->double('total_egreso');
            $table->double('total');
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
        Schema::dropIfExists('rol_pagos');
    }
};
