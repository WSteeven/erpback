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
            $table->integer('tiempo_trabajo');
            $table->text('medidas_preventivas');
            $table->unsignedBigInteger('factor_riesgo_puesto_trabajable_id');
            $table->string('factor_riesgo_puesto_trabajable_type');
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
        Schema::dropIfExists('med_fr_puestos_trabajos_actuales');
    }
};
