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
        // Respuestas a los cuestionarios publicos
        Schema::create('med_cuestionarios_publicos', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('cuestionario_id');
            $table->foreign('cuestionario_id')->on('med_cuestionarios')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('persona_id');
            $table->foreign('persona_id')->on('med_personas')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

            $table->text('respuesta_texto')->nullable();

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
        Schema::dropIfExists('med_cuestionarios_publicos');
    }
};
