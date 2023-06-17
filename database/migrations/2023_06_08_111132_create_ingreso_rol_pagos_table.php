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
        Schema::create('ingreso_rol_pago', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_rol_pago');
            $table->foreign('id_rol_pago')->references('id')->on('rol_pago');
            $table->unsignedBigInteger('concepto');
            $table->foreign('concepto')->references('id')->on('concepto_ingresos');
            $table->decimal('monto',8,2);
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
        Schema::dropIfExists('ingreso_rol_pagos');
    }
};
