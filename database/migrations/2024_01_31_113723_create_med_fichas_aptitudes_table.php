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
            $table->date('fecha_emision');
            $table->text('observaciones_aptitud_medica');
            $table->text('recomendaciones');

            //Lllaves foraneas
            $table->unsignedBigInteger('tipo_evaluacion_id');
            $table->foreign('tipo_evaluacion_id')->references('id')->on('med_tipos_aptitudes_medica_laborales')->cascadeOnUpdate();

            $table->unsignedBigInteger('tipo_aptitud_medica_laboral_id');
            $table->foreign('tipo_aptitud_medica_laboral_id','fk_tipo_aptitud_med_lab')->references('id')->on('med_tipos_aptitudes_medica_laborales')->cascadeOnUpdate();

            $table->unsignedBigInteger('tipo_evaluacion_medica_retiro_id')->nullable();
            $table->foreign('tipo_evaluacion_medica_retiro_id','fk_tipo_eval_retiro')->references('id')->on('med_tipos_evaluaciones_medica_retiros')->nullOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('preocupacional_id');
            $table->foreign('preocupacional_id')->references('id')->on('med_preocupacionales')->cascadeOnUpdate();

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
