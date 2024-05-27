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
        Schema::create('med_fichas_reintegros', function (Blueprint $table) {
            $table->id();

            $table->date('fecha_ultimo_dia_laboral');
            $table->date('fecha_reingreso');
            $table->string('causa_salida');
            $table->text('motivo_consulta');
            $table->text('enfermedad_actual')->nullable();
            $table->text('observacion_examen_fisico_regional')->nullable();

            // Foreign keys
            $table->unsignedBigInteger('cargo_id')->nullable();
            $table->foreign('cargo_id')->references('id')->on('cargos')->cascadeOnDelete()->cascadeOnUpdate();
            
            $table->unsignedBigInteger('profesional_salud_id');
            $table->foreign('profesional_salud_id')->references('id')->on('med_profesionales_salud')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('registro_empleado_examen_id');
            $table->foreign('registro_empleado_examen_id')->references('id')->on('med_registros_empleados_examenes')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_fichas_reintegros');
    }
};
