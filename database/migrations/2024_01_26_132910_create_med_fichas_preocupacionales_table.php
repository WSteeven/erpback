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
        Schema::create('med_fichas_preocupacionales', function (Blueprint $table) {
            $table->id();
            $table->text('establecimiento_salud');
            $table->string('numero_archivo')->nullable();
            $table->string('lateralidad');
            $table->string('area_trabajo')->nullable();
            $table->text('actividades_relevantes_puesto_trabajo_ocupar')->nullable();
            $table->text('motivo_consulta');
            $table->text('actividades_extralaborales')->nullable();
            $table->text('enfermedad_actual')->nullable();
            $table->text('observacion_examen_fisico_regional')->nullable();
            $table->text('recomendaciones_tratamiento')->nullable();
            $table->string('grupo_sanguineo');

            // Foreign keys
            $table->unsignedBigInteger('cargo_id');
            $table->foreign('cargo_id')->references('id')->on('cargos')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('religion_id');
            $table->foreign('religion_id')->references('id')->on('med_religiones')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('orientacion_sexual_id');
            $table->foreign('orientacion_sexual_id')->references('id')->on('med_orientaciones_sexuales')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('identidad_genero_id');
            $table->foreign('identidad_genero_id')->references('id')->on('med_identidades_generos')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('registro_empleado_examen_id');
            $table->foreign('registro_empleado_examen_id')->references('id')->on('med_registros_empleados_examenes')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('profesional_salud_id');
            $table->foreign('profesional_salud_id')->references('id')->on('empleados')->cascadeOnUpdate();

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
        Schema::dropIfExists('med_fichas_preocupacionales');
    }
};
