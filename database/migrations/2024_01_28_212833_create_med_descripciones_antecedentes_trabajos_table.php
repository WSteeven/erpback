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
        Schema::create('med_descripciones_antecedentes_trabajos', function (Blueprint $table) {
            $table->id();
            $table->boolean('calificado_iess')->default('1');
            $table->text('descripcion');
            $table->date('fecha');
            $table->text('observacion');
            $table->string('tipo_descripcion_antecedente_trabajo');

            $table->unsignedBigInteger('ficha_preocupacional_id');
            $table->foreign('ficha_preocupacional_id', 'med_descripcione_anteced_trab')->references('id')->on('med_fichas_preocupacionales')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_descripciones_antecedentes_trabajos');
    }
};
