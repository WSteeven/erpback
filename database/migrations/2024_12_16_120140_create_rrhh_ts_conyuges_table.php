<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh_ts_conyuges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ficha_id');
            $table->unsignedBigInteger('empleado_id');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('nivel_academico');
            $table->integer('edad');
            $table->string('profesion');
            $table->string('telefono');
            $table->boolean('tiene_dependencia_laboral');
            $table->decimal('promedio_ingreso_mensual');
            $table->timestamps();

            $table->foreign('ficha_id')->references('id')->on('rrhh_ts_fichas_socioeconomicas');
            $table->foreign('empleado_id')->references('id')->on('empleados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_ts_conyuges');
    }
};
