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
        Schema::create('traspasos', function (Blueprint $table) {
            $table->id();
            $table->string('justificacion')->nullable();
            $table->boolean('devuelta')->default(false);
            $table->unsignedBigInteger('solicitante_id');
            $table->unsignedBigInteger('desde_cliente_id');
            $table->unsignedBigInteger('hasta_cliente_id');
            $table->unsignedBigInteger('tarea_id')->nullable();
            $table->unsignedBigInteger('estado_id');
            $table->unsignedBigInteger('sucursal_id');
            $table->timestamps();

            $table->foreign('solicitante_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('desde_cliente_id')->references('id')->on('clientes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('hasta_cliente_id')->references('id')->on('clientes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tarea_id')->references('id')->on('tareas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('estado_id')->references('id')->on('estados_transacciones_bodega')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sucursal_id')->references('id')->on('sucursales')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('traspasos');
    }
};
