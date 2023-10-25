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
        Schema::create('vacaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->unsignedBigInteger('periodo_id');
            $table->foreign('periodo_id')->references('id')->on('periodos');
            $table->date('derecho_vacaciones');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            // $table->date('fecha_inicio_rango1_vacaciones');
            // $table->date('fecha_fin_rango1_vacaciones');
            // $table->date('fecha_inicio_rango2_vacaciones');
            $table->unsignedBigInteger('estado');
            $table->foreign('estado')->references('id')->on('autorizaciones');
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
        Schema::dropIfExists('vacaciones');
    }
};
