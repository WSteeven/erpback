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
        Schema::create('med_citas_medicas', function (Blueprint $table) {
            $table->id();
            $table->text('sintomas');
            // $table->text('motivo');
            $table->text('observacion')->nullable();
            $table->dateTime('fecha_hora_cita')->nullable();
            $table->string('estado_cita_medica');
            $table->string('motivo_rechazo');
            $table->string('motivo_cancelacion');
            $table->dateTime('fecha_hora_rechazo');
            $table->dateTime('fecha_hora_cancelado');

            //Laves foraneas
            /*$table->unsignedBigInteger('estado_cita_medica_id');
            $table->foreign('estado_cita_medica_id')->references('id')->on('med_estados_citas_medicas')->cascadeOnUpdate();*/

            $table->unsignedBigInteger('paciente_id');
            $table->foreign('paciente_id')->references('id')->on('empleados')->cascadeOnUpdate();
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
        Schema::dropIfExists('med_citas_medicas');
    }
};
