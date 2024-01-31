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
        Schema::create('med_factores_riesgos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_factor_riesgo_id');
            $table->unsignedBigInteger('categoria_factor_riesgo_id');
            $table->unsignedBigInteger('preocupacional_id');
            $table->foreign('tipo_factor_riesgo_id')->on('med_tipos_factores_riesgos')->references('id')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('categoria_factor_riesgo_id')->on('med_categorias_factores_riesgos')->references('id')->nullOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('med_factores_riesgos');
    }
};
