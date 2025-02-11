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
        Schema::table('rrhh_ts_salud_empleados', function (Blueprint $table) {
            $table->date('fecha_enfermedad_cronica')->nullable();
            $table->text('imagen_cedula_familiar_dependiente_discapacitado')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rrhh_ts_salud_empleados', function (Blueprint $table) {
          $table->dropColumn(['fecha_enfermedad_cronica','imagen_cedula_familiar_dependiente_discapacitado']);
        });
    }
};
