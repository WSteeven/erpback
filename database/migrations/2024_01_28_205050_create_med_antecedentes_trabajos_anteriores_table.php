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
        Schema::create('med_antecedentes_trabajos_anteriores', function (Blueprint $table) {
            $table->id();
            $table->string('empresa');
            $table->text('puesto_trabajo');
            $table->text('actividades_desempenaba');
            $table->integer('tiempo_trabajo_meses');
            $table->string('r_fisico');
            $table->string('r_mecanico');
            $table->string('r_quimico');
            $table->string('r_biologico');
            $table->string('r_ergonomico');
            $table->string('r_phisosocial');
            $table->text('observacion');

            // Foreign keys
            $table->unsignedBigInteger('ficha_preocupacional_id');
            $table->foreign('ficha_preocupacional_id')->on('med_fichas_preocupacionales')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_antecedentes_trabajos_anteriores');
    }
};
