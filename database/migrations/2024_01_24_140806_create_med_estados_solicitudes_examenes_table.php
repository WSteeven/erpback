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
        Schema::create('med_estados_solicitudes_examenes', function (Blueprint $table) {
            $table->id();

            $table->string('observacion');
            $table->string('fecha_hora_asistencia');

            // Foreign keys
            $table->unsignedBigInteger('registro_empleado_examen_id');
            $table->foreign('registro_empleado_examen_id','fk_registro_empledo')->references('id')->on('med_registros_empleados_examenes')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('examen_id');
            $table->foreign('examen_id')->references('id')->on('med_examenes')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('estado_examen_id');
            $table->foreign('estado_examen_id')->references('id')->on('med_estados_examenes')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('laboratorio_clinico_id');
            $table->foreign('laboratorio_clinico_id')->references('id')->on('med_laboratorios_clinicos')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_estados_solicitudes_examenes');
    }
};
