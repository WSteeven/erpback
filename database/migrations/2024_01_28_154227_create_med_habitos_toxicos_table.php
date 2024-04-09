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
        Schema::create('med_resultados_habitos_toxicos', function (Blueprint $table) {
            $table->id();
            $table->integer('tiempo_consumo_meses');
            $table->integer('tiempo_abstinencia_meses');
            // Foreign keys
            $table->unsignedBigInteger('ficha_preocupacional_id');
            $table->foreign('ficha_preocupacional_id')->on('med_fichas_preocupacionales')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('tipo_habito_toxico_id');
            $table->foreign('tipo_habito_toxico_id')->on('med_tipos_habitos_toxicos')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_resultados_habitos_toxicos');
    }
};
