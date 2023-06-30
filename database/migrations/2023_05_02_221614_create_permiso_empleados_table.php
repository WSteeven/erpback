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
        Schema::create('permiso_empleados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->unsignedBigInteger('tipo_permiso_id');
            $table->foreign('tipo_permiso_id')->references('id')->on('motivo_permiso_empleados');
            $table->dateTime('fecha_hora_inicio');
            $table->dateTime('fecha_hora_fin');
            $table->date('fecha_recuperacion');
            $table->time('hora_recuperacion');
            $table->string('justificacion');
            $table->unsignedBigInteger('estado_permiso_id');
            $table->foreign('estado_permiso_id')->references('id')->on('autorizaciones');
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
        Schema::dropIfExists('permiso_empleados');
    }
};
