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
        Schema::create('med_respuestas_cuestionarios_empleados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cuestionario_id');
            $table->foreign('cuestionario_id')->on('med_cuestionarios')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('respuesta');
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->on('empleados')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('med_respuestas_cuestionarios_empleados');
    }
};
