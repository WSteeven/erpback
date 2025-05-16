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
        Schema::create('rrhh_ts_salud_empleados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->text('discapacidades')->nullable();
            $table->string('enfermedad_cronica')->nullable();
            $table->string('alergias')->nullable();
            $table->string('lugar_atencion');
            $table->string('nombre_familiar_dependiente_discapacitado')->nullable();
            $table->string('parentesco_familiar_discapacitado')->nullable();
            $table->text('discapacidades_familiar_dependiente')->nullable();
            $table->string('frecuencia_asiste_medico')->nullable();
            $table->string('frecuencia_practica_deporte')->nullable();
            $table->string('deporte_practicado')->nullable();
            $table->unsignedBigInteger('model_id');
            $table->text('model_type');
            $table->timestamps();

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
        Schema::dropIfExists('rrhh_ts_salud_empleados');
    }
};
