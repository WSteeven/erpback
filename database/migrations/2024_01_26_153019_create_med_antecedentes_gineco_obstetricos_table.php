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
        Schema::create('med_antecedentes_gineco_obstetricos', function (Blueprint $table) {
            $table->id();
            $table->date('menarquia');
            $table->integer('ciclos');
            $table->date('fecha_ultima_menstruacion');
            $table->integer('gestas');
            $table->integer('partos');
            $table->integer('cesareas');
            $table->integer('abortos');
            $table->integer('hijos_vivos');
            $table->integer('hijos_muertos');

            // Foreign keys
            $table->unsignedBigInteger('antecedentes_personales_id');
            $table->foreign('antecedentes_personales_id', 'med_anteced_gineco_obstet')->references('id')->on('med_antecedentes_personales')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_antecedentes_gineco_obstetricos');
    }
};
