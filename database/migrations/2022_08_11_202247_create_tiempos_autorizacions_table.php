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
        Schema::create('tiempo_autorizaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('autorizacion_id');
            $table->unsignedBigInteger('transaccion_id');
            $table->date('fecha_hora')->nullable();
            $table->timestamps();


            $table->foreign('autorizacion_id')->references('id')->on('autorizaciones');
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
        Schema::dropIfExists('tiempo_autorizaciones');
    }
};
