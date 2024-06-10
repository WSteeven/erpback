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
        Schema::create('med_recetas', function (Blueprint $table) {
            $table->id();

            $table->string('rp');
            $table->string('prescripcion');

            // Foreign leys
            $table->unsignedBigInteger('consulta_medica_id');
            $table->foreign('consulta_medica_id')->references('id')->on('med_consultas_medicas')->cascadeOnUpdate();

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
        Schema::dropIfExists('med_recetas');
    }
};
