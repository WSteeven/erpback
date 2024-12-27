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
        Schema::create('rrhh_cap_evaluaciones_desempenos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluado_id');
            $table->unsignedBigInteger('evaluador_id');
            $table->decimal('calificacion')->default(0);
            $table->unsignedBigInteger('formulario_id');
            $table->json('respuestas');
            $table->timestamps();

            $table->foreign('evaluado_id')->references('id')->on('empleados');
            $table->foreign('evaluador_id')->references('id')->on('empleados');
            $table->foreign('formulario_id')->references('id')->on('rrhh_cap_formularios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_cap_evaluaciones_desempenos');
    }
};
