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
        Schema::create('licencia_empleados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado');
            $table->foreign('empleado')->references('id')->on('empleados');
            $table->unsignedBigInteger('id_tipo_licencia');
            $table->foreign('id_tipo_licencia')->references('id')->on('tipo_licencias');
            $table->unsignedBigInteger('estado');
            $table->foreign('estado')->references('id')->on('autorizaciones');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->text('justificacion');
            $table->text('documento');
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
        Schema::dropIfExists('licencia_empleados');
    }
};
