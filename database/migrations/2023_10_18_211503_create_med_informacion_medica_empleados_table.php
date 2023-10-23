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
        Schema::create('med_informacion_medica_empleados', function (Blueprint $table) {
            $table->id();
            $table->json('ficha_preocupacional');
            $table->json('ficha_aptitud');
            $table->json('ficha_ocupacional_periodico');
            $table->json('ficha_reingreso');
            $table->json('ficha_salida');
            $table->json('evaluacion_riesgo_psicosocial');
            $table->json('encuesta');

            // Foreign keys
            $table->unsignedBigInteger('registro_examen_id');
            $table->foreign('registro_examen_id')->references('id')->on('med_registros_examenes')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('med_informacion_medica_empleados');
    }
};
