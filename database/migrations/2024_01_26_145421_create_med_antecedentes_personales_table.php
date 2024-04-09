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
            $table->string('antecedentes_quirorgicos');
            $table->boolean('vida_sexual_activa')->default('0');
            $table->boolean('tiene_metodo_planificacion_familiar')->default('0');
            $table->string('tipo_metodo_planificacion_familiar');
            $table->integer('hijos_vivos');
            $table->integer('hijos_muertos');

            // Foreign keys
            $table->unsignedBigInteger('ficha_preocupacional_id');
            $table->foreign('preocupacional_id', 'med_antecedentes_foreign')->references('id')->on('med_preocupacionales')->cascadeOnDelete()->cascadeOnUpdate();

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
