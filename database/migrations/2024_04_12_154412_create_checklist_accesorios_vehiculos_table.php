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
        Schema::create('veh_checklist_accesorios_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bitacora_id');
            $table->string('botiquin');
            $table->string('extintor');
            $table->string('caja_herramientas');
            $table->string('triangulos');
            $table->string('llanta_emergencia');
            $table->string('cinturones');
            $table->string('gata');
            $table->string('portaescalera');
            $table->string('observacion_accesorios_vehiculo');
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
        Schema::dropIfExists('veh_checklist_accesorios_vehiculos');
    }
};
