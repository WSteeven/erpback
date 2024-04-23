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
        Schema::create('med_fichas_retiros', function (Blueprint $table) {
            $table->id();
            $table->string('ciu');
            $table->text('establecimiento_salud');
            $table->string('numero_historia_clinica');
            $table->string('numero_archivo');
            $table->string('puesto_trabajo')->nullable();
            $table->date('fecha_salida');
            $table->boolean('evaluacion_retiro');
            $table->text('observacion_retiro')->nullable();
            $table->text('recomendacion_tratamiento')->nullable();
            $table->unsignedBigInteger('registro_empleado_examen_id');
            $table->unsignedBigInteger('profesional_id');

            $table->timestamps();
            $table->foreign('registro_empleado_examen_id')->references('id')->on('med_registros_empleados_examenes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('profesional_id')->references('id')->on('med_profesionales_salud')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('med_fichas_retiros');
    }
};
