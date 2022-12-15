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
        Schema::create('devoluciones_transacciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detalle_producto_transaccion_id');
            $table->integer('cantidad');
            $table->timestamps();

            $table->foreign('detalle_producto_transaccion_id', 'detalle_producto_transaccion_fk')->references('id')->on('detalle_producto_transaccion')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devoluciones_transacciones');
    }
};
