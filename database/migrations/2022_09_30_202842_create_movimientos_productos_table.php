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
        Schema::create('movimientos_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventario_id'); //fk producto en inventario
            $table->unsignedBigInteger('detalle_producto_transaccion_id'); // fk en tipo de transaccion para saber que transaccion origino el movimiento del stock y para saber si es de ingreso o egreso
            $table->integer('cantidad');
            $table->integer('precio_unitario')->nullable();
            $table->integer('saldo');
            $table->timestamps();

            $table->foreign('inventario_id')->references('id')->on('inventarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('detalle_producto_transaccion_id')->references('id')->on('detalle_producto_transaccion')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimientos_productos');
    }
};
