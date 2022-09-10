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
        Schema::create('detalles_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('modelo_id');
            $table->string('serial')->nullable();
            $table->integer('precio_compra')->default(0);
            $table->unsignedBigInteger('tipo_fibra_id')->nullable();
            $table->unsignedBigInteger('hilo_id')->nullable();
            $table->integer('punta_a')->nullable();
            $table->integer('punta_b')->nullable();
            $table->integer('punta_corte')->nullable();
            $table->timestamps();

            $table->foreign('modelo_id')->references('id')->on('modelos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tipo_fibra_id')->references('id')->on('tipo_fibras')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('hilo_id')->references('id')->on('hilos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalles_productos');
    }
};
