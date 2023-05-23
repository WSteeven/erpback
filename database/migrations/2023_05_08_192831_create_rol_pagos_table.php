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
            $table->string('mes',7);
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->decimal('salario',8,2);
            $table->integer('dias');
            $table->decimal('sueldo',8,2);
            $table->decimal('decimo_tercero',8,2);
            $table->decimal('decimo_cuarto',8,2);
            $table->decimal('fondos_reserva',8,2);
            $table->decimal('alimentacion',8,2);
            $table->decimal('horas_extras',8,2);
            $table->decimal('total_ingreso',8,2);
            $table->decimal('comisiones',8,2);
            $table->decimal('iess',8,2);
            $table->decimal('anticipo',8,2);
            $table->decimal('prestamo_quirorafario',8,2);
            $table->decimal('prestamo_hipotecario',8,2);
            $table->decimal('extension_conyugal',8,2);
            $table->decimal('prestamo_empresarial',8,2);
            $table->decimal('sancion_pecuniaria',8,2);
            $table->decimal('total_egreso',8,2);
            $table->decimal('total');
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
