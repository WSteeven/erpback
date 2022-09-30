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
        Schema::create('detalle_productos_transacciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('transaccion_id');
            $table->integer('cantidad_inicial')->required(); //la cantidad solicitada/registrada al inicio de la solicitud
            $table->integer('cantidad final')->default(0)->nullable(); //la cantidad despachada/devuelta
            $table->timestamps();

            $table->foreign('producto_id')->references('id')->on('productos');
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
        Schema::dropIfExists('detalle_productos_transacciones');
    }
};
