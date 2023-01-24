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
        Schema::create('detalle_producto_transaccion', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('detalle_id');
            $table->unsignedBigInteger('inventario_id');
            $table->unsignedBigInteger('transaccion_id');
            $table->integer('cantidad_inicial')->required(); //la cantidad solicitada/registrada al inicio de la solicitud
            $table->integer('cantidad_final')->default(0)->nullable(); //la cantidad despachada/devuelta
            $table->timestamps();

            // $table->foreign('detalle_id')->references('id')->on('detalles_productos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('inventario_id')->references('id')->on('inventarios')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('transaccion_id')->references('id')->on('transacciones_bodega')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_producto_transaccion');
    }
};
