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
            $table->date('fecha_inicio_labores');
            $table->date('fecha_salida');
            $table->boolean('evaluacion_retiro');
            $table->text('observacion_retiro')->nullable();
            $table->text('recomendacion_tratamiento')->nullable();
            $table->boolean('se_realizo_evaluacion_medica_retiro')->default(false);
            $table->text('observacion_evaluacion_medica_retiro')->nullable();
            $table->text('antecedentes_clinicos_quirurgicos')->nullable();
            
            // Foreigns keys
            $table->unsignedBigInteger('cargo_id')->nullable();
            $table->foreign('cargo_id')->references('id')->on('cargos')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_fichas_retiros');
    }
};
