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
        Schema::create('devolucion_traspasos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detalle_inventario_traspaso_id');
            $table->integer('cantidad');
            $table->timestamps();

            $table->foreign('detalle_inventario_traspaso_id')->references('id')->on('detalle_inventario_traspaso')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devolucion_traspasos');
    }
};
