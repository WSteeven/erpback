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
        Schema::create('med_examenes_fisicos_regionales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categoria_examen_fisico_id');
            $table->text('observacion')->nullable();
            $table->unsignedBigInteger('examen_fisico_regionalable_id');
            $table->string('examen_fisico_regionalable_type');
            $table->timestamps();

            $table->foreign('categoria_examen_fisico_id', 'fk_categoria_examen')->on('med_categorias_examenes_fisicos')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('med_examenes_fisicos_regionales');
    }
};
