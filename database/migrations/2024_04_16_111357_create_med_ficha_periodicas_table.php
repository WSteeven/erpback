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
            
            $table->text('establecimiento_salud');
            $table->string('numero_historia_clinica');
            $table->string('numero_archivo');
            $table->string('puesto_trabajo')->nullable();
            $table->text('motivo_consulta');
            $table->text('incidentes')->nullable();
            $table->text('antecedentes_clinicos_quirurgicos')->nullable();
            $table->unsignedBigInteger('registro_empleado_examen_id');
            $table->text('enfermedad_actual')->nullable();
            $table->text('observacion_examen_fisico_regional')->nullable();
            
            $table->foreign('registro_empleado_examen_id')->references('id')->on('med_registros_empleados_examenes')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('cargo_id')->nullable();
            $table->foreign('cargo_id')->references('id')->on('cargos')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_fichas_periodicas');
    }
};
