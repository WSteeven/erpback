<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * cp=control de personal.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh_cp_asistencias', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->unsignedBigInteger('empleado_id'); // Clave foránea que apunta a empleados
            $table->timestamp('hora_ingreso')->nullable(); // Hora de ingreso
            $table->timestamp('hora_salida')->nullable(); // Hora de salida
            $table->timestamp('hora_salida_almuerzo')->nullable(); // Hora de salida para almuerzo
            $table->timestamp('hora_entrada_almuerzo')->nullable(); // Hora de entrada del almuerzo
            $table->timestamps(); // Campos created_at y updated_at

            // Relación con la tabla empleados.
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
        });
    }

    
    /**
     * Reverse the migrations.
     * cp=control de personal
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_cp_asistencias');
    }
};
