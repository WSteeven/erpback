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
            $table -> unsignedBigInteger('categoria_examen_fisico_id');
            $table->unsignedBigInteger('preocupacional_id');
            $table->foreign('categoria_examen_fisico_id')->on('med_categorias_examenes_fisicos')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('preocupacional_id')->on('med_preocupacionales')->references('id')->nullOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('med_examenes_fisicos_regionales');
    }
};
