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
        Schema::create('med_resultados_examenes_preocupacionales', function (Blueprint $table) {
            $table->id();
            $table->integer('tiempo');
            $table->text('resultados');
            $table->string('genero');

            // Foreign keys
            $table->unsignedBigInteger('antecedente_personal_id');
            $table->foreign('antecedente_personal_id', 'fk_res_exa_pre_ant_per')->on('med_antecedentes_personales')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('ficha_preocupacional_id');
            $table->foreign('ficha_preocupacional_id', 'fk_res_exa_pre_fic_pre')->on('med_fichas_preocupacionales')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_resultados_examenes_preocupacionales');
    }
};
