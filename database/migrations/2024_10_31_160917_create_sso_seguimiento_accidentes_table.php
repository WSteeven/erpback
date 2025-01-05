<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sso_seguimiento_accidentes', function (Blueprint $table) {
            $table->id();

            $table->text('condiciones_climatologicas')->nullable();
            $table->text('condiciones_laborales')->nullable();
            $table->text('autorizaciones_permisos_texto')->nullable();
            $table->text('autorizaciones_permisos_foto')->nullable();
            $table->boolean('se_notifica_riesgos_trabajo')->default(false);
            $table->text('actividades_desarrolladas')->nullable();
            $table->text('descripcion_amplia_accidente')->nullable();
            $table->text('antes_accidente')->nullable();
            $table->text('instantes_previos')->nullable();
            $table->text('durante_accidente')->nullable();
            $table->text('despues_accidente')->nullable();
            $table->text('hipotesis_causa_accidente')->nullable();
            $table->text('causas_inmediatas')->nullable();
            $table->text('causas_basicas')->nullable();
            $table->text('medidas_preventivas')->nullable();
            $table->text('seguimiento_sso')->nullable();
            $table->text('seguimiento_trabajo_social')->nullable();
            $table->text('seguimiento_rrhh')->nullable();
            $table->text('metodologia_utilizada')->nullable();
            $table->unsignedBigInteger('subtarea_id')->nullable();
            $table->unsignedBigInteger('accidente_id');

            // Foreign keys
            $table->foreign('subtarea_id')->references('id')->on('subtareas')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('accidente_id')->references('id')->on('sso_accidentes')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('sso_seguimiento_accidentes');
    }
};
