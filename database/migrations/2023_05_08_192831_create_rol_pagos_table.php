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
            $table->decimal('salario');
            $table->integer('dias');
            $table->decimal('sueldo');
            $table->decimal('decimo_tercero');
            $table->decimal('decimo_cuarto');
            $table->decimal('fondos_reserva');
            $table->decimal('bonificacion');
            $table->decimal('total_ingreso');
            $table->decimal('comisiones');
            $table->decimal('iess');
            $table->decimal('anticipo');
            $table->decimal('prestamo_quirorafario');
            $table->decimal('prestamo_hipotecario');
            $table->decimal('extension_conyugal');
            $table->decimal('prestamo_empresarial');
            $table->decimal('bono_recurente');
            $table->decimal('total_egreso');
            $table->decimal('total');
            $table->enum('estado', [RolPago::CANCELADO, RolPago::CREADO, RolPago::EJECUTANDO, RolPago::REALIZADO, RolPago::FINALIZADO])->default(RolPago::CREADO);
            $table->unsignedBigInteger('rol_pago_id');
            $table->foreign('rol_pago_id')->references('id')->on('rol_pago_mes')->onDelete('cascade')->onUpdate('cascade');
            $table->text('rol_firmado');
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
