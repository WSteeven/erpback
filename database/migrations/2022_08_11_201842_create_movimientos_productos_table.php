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
        Schema::create('movimientos_de_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id'); //fk productos en percha
            $table->integer('cantidad');
            $table->integer('precio_unitario');
            $table->integer('precio_total');
            $table->timestamps();

            $table->foreign('producto_id')->references('id')->on('nombres_de_productos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimientos_de_productos');
    }
};
