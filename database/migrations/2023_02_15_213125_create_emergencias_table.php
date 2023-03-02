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
        Schema::create('emergencias', function (Blueprint $table) {
            $table->id();

            $table->string('regional');
            $table->string('atencion');
            $table->string('tipo_intervencion');
            $table->string('causa_intervencion');
            $table->string('fecha_reporte_problema');
            $table->string('hora_reporte_problema');
            $table->string('fecha_arribo');
            $table->string('hora_arribo');
            $table->string('fecha_fin_reparacion');
            $table->string('hora_fin_reparacion');
            $table->string('fecha_retiro_personal');
            $table->string('hora_retiro_personal');
            $table->string('tiempo_espera_adicional');
            $table->string('estacion_referencia_afectacion');
            $table->string('distancia_afectacion');
            $table->json('trabajo_realizado');
            $table->json('observaciones');
            $table->json('materiales_ocupados');

            // Foreign keys
            $table->unsignedBigInteger('trabajo_id');
            $table->foreign('trabajo_id')->references('id')->on('trabajos')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('emergencias');
    }
};
