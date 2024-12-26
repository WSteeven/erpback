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
        Schema::create('rrhh_cp_atrasos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id'); // Relación con la tabla de empleados
            $table->unsignedBigInteger('asistencia_id'); // Relación con la tabla de asistencias
            $table->date('fecha_atraso'); // Fecha de atraso
            $table->integer('minutos_atraso')->default(0); // Minutos de atraso calculados
            $table->integer('segundos_atraso')->default(0); // Segundos de atraso calculados
            $table->boolean('requiere_justificacion')->default(false); // Indicador si requiere justificación
            $table->text('justificacion_atraso')->nullable(); // Justificación del atraso
            $table->timestamps();

            // Definimos las claves foráneas
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
            $table->foreign('asistencia_id')->references('id')->on('rrhh_cp_asistencias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_cp_atrasos');
    }
};
