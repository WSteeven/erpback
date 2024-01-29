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
        Schema::create('med_examenes_preocupacionales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('tiempo');
            $table->text('resultados');
            $table->string('genero');
            $table->unsignedBigInteger('antecedente_personal_id');
            $table->foreign('antecedente_personal_id')->on('med_antecedentes_personales')->references('id')->nullOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_examenes_preocupacionales');
    }
};
