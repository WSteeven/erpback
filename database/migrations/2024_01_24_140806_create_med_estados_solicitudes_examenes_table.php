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
        Schema::create('med_estados_solicitudes_examenes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('registro_empleado_examen_id');
            $table->unsignedBigInteger('examen_id');
            $table->unsignedBigInteger('estado_examen_id');

            $table->foreign('registro_empleado_examen_id','fk_registro_empledo')->references('id')->on('med_registros_empleados_examenes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('examen_id')->references('id')->on('med_examenes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('estado_examen_id')->references('id')->on('med_estados_examenes')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_estados_solicitudes_examenes');
    }
};
