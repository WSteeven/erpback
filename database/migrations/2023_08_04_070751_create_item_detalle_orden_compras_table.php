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
        Schema::create('cmp_item_detalle_orden_compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orden_compra_id');
            $table->unsignedBigInteger('detalle_id');
            $table->integer('cantidad');
            $table->integer('porcentaje_descuento')->default(0);
            $table->boolean('facturable')->default(true);
            $table->boolean('graba_iva')->default(true);
            $table->double('precio_unitario');
            $table->double('iva');
            $table->double('subtotal');
            $table->double('total');
            $table->timestamps();

            $table->foreign('orden_compra_id')->references('id')->on('cmp_ordenes_compras')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('detalle_id')->references('id')->on('detalles_productos')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmp_item_detalle_orden_compras');
    }
};
