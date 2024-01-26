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
        Schema::create('med_detalles_examenes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('tipo_examen_id');
            $table->foreign('tipo_examen_id')->references('id')->on('med_tipos_examenes')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('categoria_examen_id');
            $table->foreign('categoria_examen_id')->references('id')->on('med_categorias_examenes')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('examen_id');
            $table->foreign('examen_id')->references('id')->on('med_examenes')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_detalles_examenes');
    }
};
