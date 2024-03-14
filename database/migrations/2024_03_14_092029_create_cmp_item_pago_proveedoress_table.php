<?php

use App\Models\ComprasProveedores\ItemPagoProveedores;
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
        Schema::create('cmp_item_pago_proveedor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pago_proveedor_id');
            $table->string('proveedor');
            $table->string('razon_social');
            $table->string('tipo_documento');
            $table->string('num_documento');
            $table->string('fecha_emision');
            $table->string('fecha_vencimiento');
            $table->string('centro_costo')->nullable();
            $table->enum('plazo', [ItemPagoProveedores::POR_VENCER, ItemPagoProveedores::TREINTA_DIAS, ItemPagoProveedores::SESENTA_DIAS, ItemPagoProveedores::NOVENTA_DIAS, ItemPagoProveedores::CIENTO_VEINTE_DIAS, ItemPagoProveedores::MAYOR_TIEMPO])->nullable();
            $table->double('total')->nullable();
            $table->text('descripcion')->nullable();
            $table->double('valor_documento')->default(0);
            $table->double('retenciones')->default(0);
            $table->double('pagos')->default(0);
            $table->double('valor_pagar')->default(0);
            $table->timestamps();

            $table->foreign('pago_proveedor_id')->references('id')->on('cmp_pagos_proveedores')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmp_item_pago_proveedor');
    }
};
