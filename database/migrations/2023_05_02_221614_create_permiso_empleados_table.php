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
            $table->unsignedBigInteger('motivo_id');
            $table->foreign('motivo_id')->references('id')->on('motivo_permiso_empleados');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('justificacion');
            $table->unsignedBigInteger('estado_permiso_id');
            $table->foreign('estado_permiso_id')->references('id')->on('estado_permiso_empleados');
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados');
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
