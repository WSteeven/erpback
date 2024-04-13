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
        Schema::create('med_detalle_categ_factor_riesgo_fr_puesto_trab_acts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categoria_factor_riesgo_id');
            $table->foreign('categoria_factor_riesgo_id')->references('id')->on('med_categorias_factores_riesgos');

            $table->unsignedBigInteger('fr_puesto_trabajo_actual_id');
            $table->foreign('fr_puesto_trabajo_actual_id')->references('id')->on('med_fr_puestos_trabajos_actuales');

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
        Schema::dropIfExists('med_detalle_categ_factor_riesgo_fr_puesto_trab_acts');
    }
};
