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
        Schema::create('med_consultas_medicas', function (Blueprint $table) {
            $table->id();
            $table->string('observacion');
            $table->boolean('dado_alta')->default(false);
            $table->unsignedInteger('dias_descanso')->default(0);

            // Foreign leys
            $table->unsignedBigInteger('cita_medica_id')->nullable();
            $table->foreign('cita_medica_id')->references('id')->on('med_citas_medicas')->cascadeOnUpdate();

            $table->unsignedBigInteger('registro_empleado_examen_id')->nullable();
            $table->foreign('registro_empleado_examen_id')->references('id')->on('med_registros_empleados_examenes')->cascadeOnUpdate();

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
        Schema::dropIfExists('med_consultas_medicas');
    }
};
