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
            $table->unsignedBigInteger('producto_inventario_id'); //fk productos en percha
            $table->integer('ingresos');
            $table->integer('egresos');
            $table->integer('saldo');
            $table->timestamps();

            $table->foreign('producto_inventario_id')->references('id')->on('inventarios')->onDelete('cascade')->onUpdate('cascade');
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
