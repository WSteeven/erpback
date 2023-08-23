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
        Schema::create('cmp_item_detalle_prefactura', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('prefactura_id');
            $table->unsignedBigInteger('unidad_medida_id');
            $table->text('descripcion');
            $table->integer('cantidad');
            $table->integer('porcentaje_descuento')->default(0);
            $table->double('descuento');
            $table->boolean('facturable')->default(true);
            $table->boolean('grava_iva')->default(true);
            $table->double('precio_unitario');
            $table->double('iva');
            $table->double('subtotal');
            $table->double('total');
            $table->timestamps();

            $table->foreign('prefactura_id')->references('id')->on('cmp_prefacturas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('unidad_medida_id')->references('id')->on('unidades_medidas')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmp_item_detalle_prefactura');
    }
};
