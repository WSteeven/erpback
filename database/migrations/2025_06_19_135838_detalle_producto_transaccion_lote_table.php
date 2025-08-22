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
        Schema::create('detalle_producto_transaccion_lote', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detalle_producto_id');
            $table->unsignedBigInteger('lote_id');
            $table->integer('cantidad');
            $table->timestamps();

            $table->foreign('detalle_producto_id')->references('id')->on('detalle_producto_transaccion');
            $table->foreign('lote_id')->references('id')->on('bod_lotes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
     Schema::dropIfExists('detalle_producto_transaccion_lote');
    }
};
