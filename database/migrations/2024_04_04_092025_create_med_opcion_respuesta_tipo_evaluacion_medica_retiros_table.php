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
        Schema::create('med_opciones_respuestas_tipos_evaluaciones_medicas_retiros', function (Blueprint $table) {
            $table->id();
            $table->string('respuesta');

            // Foreign keys
            $table->unsignedBigInteger('tipo_evaluacion_medica_retiro_id');
            $table->foreign('tipo_evaluacion_medica_retiro_id', 'fk_opc_resp_tips_eval_med_ret_tipo_eval')->references('id')->on('med_tipos_evaluaciones_medica_retiros')->cascadeOnUpdate();

            $table->unsignedBigInteger('ficha_aptitud_id');
            $table->foreign('ficha_aptitud_id', 'fk_opc_resp_tips_eval_med_ret_fic_apt')->references('id')->on('med_fichas_aptitudes')->cascadeOnUpdate();

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
        Schema::dropIfExists('med_opciones_respuestas_tipos_evaluaciones_medicas_retiros');
    }
};
