<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh_ts_economias_familiares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_id');
            $table->unsignedBigInteger('empleado_id');
            $table->longText('ingresos');
            $table->decimal('eg_vivienda')->default(0);
            $table->decimal('eg_servicios_basicos')->default(0);
            $table->decimal('eg_educacion')->default(0);
            $table->decimal('eg_salud')->default(0);
            $table->decimal('eg_vestimenta')->default(0);
            $table->decimal('eg_alimentacion')->default(0);
            $table->decimal('eg_transporte')->default(0);
            $table->decimal('eg_prestamos')->default(0);
            $table->decimal('eg_otros_gastos')->default(0);
            $table->timestamps();

            $table->foreign('visita_id')->references('id')->on('rrhh_ts_visitas_domiciliarias');
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
        Schema::dropIfExists('rrhh_ts_economias_familiares');
    }
};
