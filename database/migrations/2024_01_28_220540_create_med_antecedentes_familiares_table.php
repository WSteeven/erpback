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
        Schema::create('med_antecedentes_familiares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_antecedente_familiares_id');
            $table->unsignedBigInteger('ficha_preocupacional_id');
            $table->foreign('ficha_preocupacional_id')->on('med_preocupacionales')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('tipo_antecedente_familiares_id', 'med_antecedentes_familiares')->references('id')->on('med_tipos_antecedentes_familiares')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('med_antecedente_familiars');
    }
};
