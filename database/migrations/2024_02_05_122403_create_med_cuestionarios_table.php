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
        Schema::create('med_cuestionarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pregunta_id');
            $table->foreign('pregunta_id')->on('med_preguntas')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('respuesta_id')->nullable();
            $table->foreign('respuesta_id')->on('med_respuestas')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->unsignedBigInteger('tipo_cuestionario_id');
            $table->foreign('tipo_cuestionario_id')->on('med_tipos_cuestionarios')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('med_cuestionarios');
    }
};
