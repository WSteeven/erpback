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
            $table->unsignedBigInteger('preocupacional_id');
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
        Schema::dropIfExists('med_descripciones_antecedentes_trabajos');
    }
};
