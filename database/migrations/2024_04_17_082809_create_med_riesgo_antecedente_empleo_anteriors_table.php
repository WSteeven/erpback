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
        Schema::create('med_riesgos_antecedentes_trabajos_anteriores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_riesgo_id')->nullable();
            $table->unsignedBigInteger('antecedente_id');
            $table->timestamps();

            $table->foreign('tipo_riesgo_id', 'fk_tipo_riesgo')->references('id')->on('med_tipos_factores_riesgos')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('antecedente_id', 'fk_antecedente')->references('id')->on('med_antecedentes_trabajos_anteriores')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('med_riesgos_antecedentes_trabajos_anteriores');
    }
};
