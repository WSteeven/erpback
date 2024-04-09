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
        Schema::create('med_actividades_fisicas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_actividad');
            $table->integer('tiempo');

            // Foreign keys
            $table->unsignedBigInteger('ficha_preocupacional_id');
            $table->foreign('ficha_preocupacional_id')->on('med_preocupacionales')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_actividades_fisicas');
    }
};
