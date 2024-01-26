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
        Schema::create('med_preocupacionales', function (Blueprint $table) {
            $table->id();
            $table->string('ciu');
            $table->text('esatblecimiento_salud');
            $table->string('numero_historia_clinica');
            $table->string('numero_archivo');
            $table->string('puesto_trabajo');
            $table->unsignedBigInteger('religion_id');
            $table->unsignedBigInteger('orientacion_sexual_id');
            $table->unsignedBigInteger('identidad_genero_id');
            $table->integer('porcentaje_discapacidad');
            $table->text('actividades_relevantes_puesto_trabajo_ocupar');
            $table->text('motivo_consulta');
            $table->unsignedBigInteger('empleado_id');
            $table->boolean('actividad_fisica')->default('0');
            $table->boolean('consume_medicacion')->default('0');
            $table->string('enfermedad_actual');
            $table->text('recomendaciones_tratamiento');
            $table->foreign('religion_id')->on('med_religiones')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('orientacion_sexual_id')->on('med_orientaciones_sexuales')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('identidad_genero_id')->on('med_identidades_generos')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('empleado_id')->on('empleados')->references('id')->nullOnDelete()->cascadeOnUpdate();
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
