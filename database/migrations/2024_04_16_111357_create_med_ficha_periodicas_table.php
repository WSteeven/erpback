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
        Schema::create('med_fichas_periodicas', function (Blueprint $table) {
            $table->id();
            $table->string('ciu');
            $table->text('establecimiento_salud');
            $table->string('numero_historia_clinica');
            $table->string('numero_archivo');
            $table->string('puesto_trabajo')->nullable();
            $table->text('motivo_consulta');
            $table->text('incidentes');
            $table->unsignedBigInteger('registro_empleado_examen_id');            
            $table->text('enfermedad_actual')->nullable();
            $table->text('observacion_examen_fisico_regional')->nullable();
            $table->timestamps();

            $table->foreign('registro_empleado_examen_id')->references('id')->on('med_registros_empleados_examenes')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('med_fichas_periodicas');
    }
};
