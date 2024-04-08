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
            $table->string('ciu');
            $table->text('esatblecimiento_salud');
            $table->string('numero_historia_clinica');
            $table->string('numero_archivo');
            $table->string('puesto_trabajo');
            $table->integer('porcentaje_discapacidad');
            $table->text('actividades_relevantes_puesto_trabajo_ocupar');
            $table->text('motivo_consulta');
            $table->boolean('actividad_fisica')->default('0');
            $table->boolean('consume_medicacion')->default('0');
            $table->string('enfermedad_actual');
            $table->text('recomendaciones_tratamiento');
            $table->text('descripcion_examen_fisico_regional');
            $table->text('descripcion_revision_organos_sistemas');

            // Foreign keys
            $table->unsignedBigInteger('religion_id');
            $table->foreign('religion_id')->references('id')->on('med_religiones')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('orientacion_sexual_id');
            $table->foreign('orientacion_sexual_id')->references('id')->on('med_orientaciones_sexuales')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('identidad_genero_id');
            $table->foreign('identidad_genero_id')->references('id')->on('med_identidades_generos')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_preocupacionales');
    }
};
