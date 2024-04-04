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
        Schema::create('med_fichas_aptitudes', function (Blueprint $table) {
            $table->id();

            $table->text('recomendaciones')->nullable();
            $table->text('observaciones_aptitud_medica')->nullable();
            $table->boolean('firmado_profesional_salud')->default(false);
            $table->boolean('firmado_paciente')->default(false);

            // Foreigns keys
            $table->unsignedBigInteger('registro_empleado_examen_id');
            $table->foreign('registro_empleado_examen_id')->references('id')->on('med_registros_empleados_examenes')->cascadeOnUpdate();

            $table->unsignedBigInteger('tipo_aptitud_medica_laboral_id');
            $table->foreign('tipo_aptitud_medica_laboral_id', 'fk_tipo_aptitud_med_lab')->references('id')->on('med_tipos_aptitudes_medica_laborales')->cascadeOnUpdate();

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
        Schema::dropIfExists('med_fichas_aptitudes');
    }
};
