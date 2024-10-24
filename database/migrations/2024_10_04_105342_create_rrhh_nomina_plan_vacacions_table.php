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
        Schema::create('rrhh_nomina_planes_vacaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('periodo_id');
            $table->unsignedBigInteger('empleado_id');
            $table->integer('rangos')->default(1);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->date('fecha_inicio_primer_rango')->nullable();
            $table->date('fecha_fin_primer_rango')->nullable();
            $table->date('fecha_inicio_segundo_rango')->nullable();
            $table->date('fecha_fin_segundo_rango')->nullable();
            $table->timestamps();

            $table->foreign('periodo_id')->references('id')->on('periodos');
            $table->foreign('empleado_id')->references('id')->on('empleados');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_nomina_planes_vacaciones');
    }
};
