<?php

use App\Models\EstadoTransaccion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Src\Config\EstadosTransacciones;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas_cortes_pagos_comisiones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->enum('estado', [EstadoTransaccion::PENDIENTE, EstadoTransaccion::COMPLETA, 'ANULADA'])->default(EstadoTransaccion::PENDIENTE);
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
        Schema::dropIfExists('ventas_cortes_pagos_comisiones');
    }
};
