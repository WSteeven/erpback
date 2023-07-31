<?php

use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
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
            $table->decimal('bonificacion',8,2);
            $table->decimal('total_ingreso',8,2);
            $table->decimal('comisiones',8,2);
            $table->decimal('iess',8,2);
            $table->decimal('anticipo',8,2);
            $table->decimal('prestamo_quirorafario',8,2);
            $table->decimal('prestamo_hipotecario',8,2);
            $table->decimal('extension_conyugal',8,2);
            $table->decimal('prestamo_empresarial',8,2);
            $table->decimal('bono_recurente',8,2);
            $table->decimal('total_egreso',8,2);
            $table->decimal('total');
            $table->enum('estado', [RolPago::CANCELADO, RolPago::CREADO, RolPago::EJECUTANDO, RolPago::REALIZADO, RolPago::FINALIZADO]);
            $table->unsignedBigInteger('rol_pago_id');
            $table->foreign('rol_pago_id')->references('id')->on('rol_pago_mes')->onDelete('cascade')->onUpdate('cascade');
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
