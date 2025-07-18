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
        Schema::create('sso_consulta_medica_seguimiento_accidentes', function (Blueprint $table) {
            $table->id();
            $table->string('certificado_alta');
            $table->string('observacion_alta');
            $table->string('restricciones');
            $table->unsignedBigInteger('seguimiento_accidente_id');
            $table->unsignedBigInteger('consulta_medica_id');

            // Foreign keys
            $table->foreign('seguimiento_accidente_id', 'fk_seg_acc')->references('id')->on('sso_seguimiento_accidentes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('consulta_medica_id', 'fk_cons_med')->references('id')->on('med_consultas_medicas')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('sso_consulta_medica_seguimiento_accidentes');
    }
};
