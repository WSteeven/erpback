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
        Schema::create('ventas_ventas', function (Blueprint $table) {
            $table->id();
            $table->string('orden_id');
            $table->string('orden_interna')->nullable();
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->unsignedBigInteger('vendedor_id')->nullable();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->date('fecha_activacion')->nullable();
            $table->string('estado_activacion');
            $table->string('forma_pago');
            $table->unsignedBigInteger('comision_id');
            $table->decimal('chargeback', 8, 4);
            $table->decimal('comision_vendedor', 8, 4);
            $table->boolean('comisiona')->default('0');
            $table->boolean('activo')->default(true);
            $table->text('observacion')->nullable();
            $table->boolean('primer_mes')->default(false);
            $table->timestamp('fecha_pago_primer_mes')->nullable();
            $table->boolean('comision_pagada')->default(false);
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('ventas_clientes_claro')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('supervisor_id')->references('empleado_id')->on('ventas_vendedores')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('vendedor_id')->references('empleado_id')->on('ventas_vendedores')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('producto_id')->references('id')->on('ventas_productos_ventas')->cascadeOnUpdate();
            $table->foreign('comision_id')->references('id')->on('ventas_comisiones')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_ventas');
    }
};
