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
        Schema::create('seg_visitantes', function (Blueprint $table) {
            $table->id();

            $table->string('nombre_completo');
            $table->string('identificacion');
            $table->string('celular')->nullable();
            $table->string('motivo_visita');
            $table->string('persona_visitada');
            $table->string('placa_vehiculo')->nullable();
            // $table->string('fecha_hora_ingreso');
            // $table->string('fecha_hora_salida')->nullable();
            $table->string('observaciones')->nullable();
            $table->foreignId('actividad_bitacora_id')->constrained('seg_actividades_bitacora')->onDelete('cascade');

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
        Schema::dropIfExists('seg_visitantes');
    }
};
