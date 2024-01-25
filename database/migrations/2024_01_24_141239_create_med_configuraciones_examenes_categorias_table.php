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
        Schema::create('med_configuraciones_examenes_categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');

            // Foreign keys
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
        Schema::dropIfExists('med_configuraciones_examenes_categorias');
    }
};
