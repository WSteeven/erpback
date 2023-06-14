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
        Schema::create('prestamo_empresarial', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // Nueva columna autoincremental como clave primaria
            $table->unsignedBigInteger('solicitante');
            $table->foreign('solicitante')->references('id')->on('empleados');
            $table->date('fecha');
            $table->decimal('monto', 8, 2);
            $table->integer('utilidad')->nullable();
            $table->decimal('valor_utilidad', 8, 2)->nullable();
            $table->unsignedBigInteger('id_forma_pago');
            $table->foreign('id_forma_pago')->references('id')->on('forma_pagos');
            $table->decimal('plazo', 8, 2);
            $table->enum('estado', ['ACTIVO', 'FINALIZADO']);
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
        Schema::dropIfExists('prestamo_empresarials');
    }
};
