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
        Schema::create('med_antecedentes_personales', function (Blueprint $table) {
            $table->id();
            $table->boolean('vida_sexual_activa')->default(false);
            $table->boolean('tiene_metodo_planificacion_familiar')->default(false);
            $table->string('tipo_metodo_planificacion_familiar')->nullable();
            $table->integer('hijos_vivos')->default(0);
            $table->integer('hijos_muertos')->default(0);

            // Foreign keys
            $table->unsignedBigInteger('ficha_preocupacional_id');
            $table->foreign('ficha_preocupacional_id')->references('id')->on('med_fichas_preocupacionales')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_antecedente_personals');
    }
};
