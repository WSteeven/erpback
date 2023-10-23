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
        Schema::create('med_examenes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->json('ids_cargos_acceso')->nullable();

            // Foreign keys
            $table->unsignedBigInteger('categoria_examen_id');
            $table->foreign('categoria_examen_id')->references('id')->on('med_categorias_examenes')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('med_examenes');
    }
};
