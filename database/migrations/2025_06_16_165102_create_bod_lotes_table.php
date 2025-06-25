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
        Schema::create('bod_lotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventario_id');
            $table->unsignedBigInteger('transaccion_id');
            $table->integer('cant_ingresada');
            $table->integer('cant_disponible');
            $table->date('fecha_vencimiento');
            $table->timestamps();

            $table->foreign('inventario_id')->references('id')->on('inventarios');
            $table->foreign('transaccion_id')->references('id')->on('transacciones_bodega');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bod_lotes');
    }
};
