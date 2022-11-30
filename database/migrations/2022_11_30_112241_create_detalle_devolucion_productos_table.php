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
        Schema::create('detalle_devolucion_producto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detalle_id');
            $table->unsignedBigInteger('devolucion_id');
            $table->integer('cantidad');
            $table->timestamps();

            $table->foreign('detalle_id')->references('id')->on('detalles_productos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('devolucion_id')->references('id')->on('devoluciones')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_devolucion_producto');
    }
};
