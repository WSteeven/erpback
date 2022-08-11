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
        Schema::create('tiempo_estado_transacciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estado_id');
            $table->unsignedBigInteger('transaccion_id');
            $table->dateTime('fecha');
            $table->timestamps();

            $table->foreign('estado_id')->references('id')->on('estados_transacciones_bodega');
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
        Schema::dropIfExists('tiempo_estado_transacciones');
    }
};
