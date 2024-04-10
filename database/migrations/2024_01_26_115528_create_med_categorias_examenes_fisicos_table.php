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
        Schema::create('med_categorias_examenes_fisicos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');

            // Foreign keys
            $table->unsignedBigInteger('region_cuerpo_id');
            $table->foreign('region_cuerpo_id')->references('id')->on('med_regiones_cuerpo')->cascadeOnUpdate()->cascadeOnDelete();

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
        Schema::dropIfExists('med_categorias_examenes_fisicos');
    }
};
