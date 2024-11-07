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
        Schema::create('rrhh_nomina_solicitudes_vacaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('autorizador_id');
            $table->unsignedBigInteger('autorizacion_id');
            $table->unsignedBigInteger('periodo_id');
            $table->integer('dias_solicitados');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->unsignedBigInteger('reemplazo_id');
            $table->text('funciones');
            $table->timestamps();

            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('autorizador_id')->references('id')->on('empleados');
            $table->foreign('reemplazo_id')->references('id')->on('empleados');
            $table->foreign('autorizacion_id')->references('id')->on('autorizaciones');
            $table->foreign('periodo_id')->references('id')->on('periodos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_nomina_solicitudes_vacaciones');
    }
};
