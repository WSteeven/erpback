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
        Schema::create('ventas_detalles_pagos_comisiones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->unsignedBigInteger('corte_id')->nullable();
            $table->unsignedBigInteger('vendedor_id')->nullable();
            $table->decimal('chargeback', 8, 4)->nullable();
            $table->integer('ventas')->nullable();
            $table->decimal('valor', 8, 4);
            $table->boolean('pagado')->default(false);
            $table->timestamps();

            $table->foreign('vendedor_id')->references('empleado_id')->on('ventas_vendedores')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('corte_id')->references('id')->on('ventas_cortes_pagos_comisiones')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_detalles_pagos_comisiones');
    }
};
