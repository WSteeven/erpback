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
        Schema::create('veh_checklist_imagenes_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bitacora_id');
            $table->string('imagen_frontal')->nullable();
            $table->string('imagen_trasera')->nullable();
            $table->string('imagen_lateral_derecha')->nullable();
            $table->string('imagen_lateral_izquierda')->nullable();
            $table->string('imagen_tablero_km')->nullable();
            $table->string('imagen_tablero_radio')->nullable();
            $table->string('imagen_asientos')->nullable();
            $table->string('imagen_accesorios')->nullable();
            $table->string('imagen_accesorios')->nullable();
            $table->string('observacion');
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
        Schema::dropIfExists('veh_checklist_imagenes_vehiculos');
    }
};
