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
        Schema::create('veh_checklist_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bitacora_id');
            $table->string('parabrisas');
            $table->string('limpiaparabrisas');
            $table->string('luces_interiores');
            $table->string('aire_acondicionado');
            $table->string('aceite_motor');
            $table->string('liquido_freno');
            $table->string('aceite_hidraulico');
            $table->string('liquido_refrigerante');
            $table->string('filtro_combustible');
            $table->string('bateria');
            $table->string('agua_plumas_radiador');
            $table->string('cables_conexiones');
            $table->string('luces_exteriores');
            $table->string('frenos');
            $table->string('amortiguadores');
            $table->string('llantas');
            $table->string('observacion_checklist_interior');
            $table->string('observacion_checklist_bajo_capo');
            $table->string('observacion_checklist_exterior');
            $table->timestamps();

            $table->foreign('bitacora_id')->references('id')->on('veh_bitacoras_vehiculos')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veh_checklist_vehiculos');
    }
};
