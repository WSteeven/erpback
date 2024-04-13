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
        Schema::create('med_fr_puestos_trabajos_actuales', function (Blueprint $table) {
            $table->id();
            $table->string('puesto_trabajo');
            $table->text('actividad');
            $table->text('medidas_preventivas');
            $table->timestamps();
            $table->unsignedBigInteger('ficha_preocupacional_id');
            $table->foreign('ficha_preocupacional_id')->on('med_fichas_preocupacionales')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('med_fr_puestos_trabajos_actuales');
    }
};
