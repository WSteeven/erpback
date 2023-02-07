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
        Schema::create('viaticos', function (Blueprint $table) {
            $table->integer('id')->increment();
            $table->date('fecha_viat');
            $table->integer('id_lugar', 12);
            $table->string('num_tarea', 25);
            $table->string('ruc', 13);
            $table->string('factura', 25);
            $table->string('proveedor', 250);
            $table->string('aut_especial', 300);
            $table->integer('detalle', 12);
            $table->integer('sub_detalle', 12);
            $table->integer('cant',3);
            $table->decimal('valor_u', 19, 2);
            $table->decimal('total', 19, 2);
            $table->string('comprobante', 2500);
            $table->string('comprobante2', 2500);
            $table->string('observacion', 2500);
            $table->integer('id_usuario', 12);
            $table->integer('estado', 12);
            $table->string('detalle_estado', 2500);
            $table->dateTime('fecha_ingreso');
            $table->dateTime('fecha_proc');
            $table->string('transcriptor',120);
            $table->timestamp('fecha_trans');
            $table->foreign('estado')->references('id')->on('estado_viatico');
            $table->foreign('detalle')->references('id')->on('detalle_viaticos');
            $table->foreign('id_lugar')->references('id')->on('parroquia');
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
