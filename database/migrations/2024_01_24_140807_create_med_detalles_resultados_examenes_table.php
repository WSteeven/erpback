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
        Schema::create('med_detalles_resultados_examenes', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('estado_solicitud_examen_id');
            $table->foreign('estado_solicitud_examen_id', 'fk_det_res_exa_est_sol_exa')->references('id')->on('med_estados_solicitudes_examenes')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_detalles_resultados_examenes');
    }
};
