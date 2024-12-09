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
        Schema::table('rrhh_cp_asistencias', function (Blueprint $table) {
            $table->time('hora_ingreso')->nullable()->change(); // Cambiar a formato TIME
            $table->time('hora_salida')->nullable()->change(); // Cambiar a formato TIME
            $table->time('hora_salida_almuerzo')->nullable()->change(); // Cambiar a formato TIME
            $table->time('hora_entrada_almuerzo')->nullable()->change(); // Cambiar a formato TIME
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rrhh_cp_asistencias', function (Blueprint $table) {
            $table->timestamp('hora_ingreso')->nullable()->change(); // Revertir a TIMESTAMP
            $table->timestamp('hora_salida')->nullable()->change(); // Revertir a TIMESTAMP
            $table->timestamp('hora_salida_almuerzo')->nullable()->change(); // Revertir a TIMESTAMP
            $table->timestamp('hora_entrada_almuerzo')->nullable()->change(); // Revertir a TIMESTAMP
        });
    }
};
