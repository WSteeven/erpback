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
        Schema::create('ventas_pagos_comisiones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->unsignedBigInteger('vendedor_id')->nullable();
            $table->decimal('chargeback', 8, 4)->nullable();
            $table->decimal('valor', 8, 4);
            $table->timestamps();
            
            $table->foreign('vendedor_id')->references('empleado_id')->on('ventas_vendedores')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_pagos_comisiones');
    }
};
