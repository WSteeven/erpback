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
        Schema::create('med_resultados_examenes', function (Blueprint $table) {
            $table->id();

            $table->double('resultado');
            $table->double('fecha_examen');

            $table->unsignedBigInteger('configuracion_examen_campo_id');
            $table->foreign('configuracion_examen_campo_id')->references('id')->on('med_configuraciones_examenes_campos')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('estado_solicitud_examen_id');
            $table->foreign('estado_solicitud_examen_id')->references('id')->on('med_estados_solicitudes_examenes')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('med_resultados_examenes');
    }
};
