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
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_viat');
            $table->unsignedBigInteger('id_lugar');
            $table->unsignedBigInteger('id_tarea')->nullable();
            $table->unsignedBigInteger('id_subtarea')->nullable();
            $table->unsignedBigInteger('id_proyecto')->nullable();
            $table->string('ruc', 13);
            $table->string('factura', 25)->nullable();
            $table->string('numComprobante', 250)->nullable();
            $table->string('proveedor', 250);
            $table->unsignedBigInteger('aut_especial');
            $table->unsignedBigInteger('detalle');
            $table->integer('cant')->length(3);;
            $table->decimal('valor_u',19,2);
            $table->decimal('total',19,2);
            $table->string('comprobante', 2500);
            $table->string('comprobante2', 2500);
            $table->string('observacion', 2500)->nullable();
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('estado');
            $table->string('detalle_estado', 250)->nullable();
            $table->foreign('estado')->references('id')->on('estado_viatico');
            $table->foreign('detalle')->references('id')->on('detalle_viatico');
            $table->foreign('id_lugar')->references('id')->on('cantones');
            $table->foreign('aut_especial')->references('id')->on('users');
            $table->foreign('id_usuario')->references('id')->on('users');
            $table->foreign('id_tarea')->references('id')->on('tareas');
            $table->foreign('id_subtarea')->references('id')->on('subtareas');
            $table->foreign('id_proyecto')->references('id')->on('proyectos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('viaticos');
    }
};
