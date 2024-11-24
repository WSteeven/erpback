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
        Schema::create('rrhh_cp_horario_laboral', function (Blueprint $table) {
            $table->id();
            $table->time('hora_entrada')->nullable()->comment('Hora de entrada al trabajo');
            $table->time('hora_salida')->nullable()->comment('Hora de salida del trabajo');
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
        Schema::dropIfExists('rrhh_cp_horario_laboral');
    }
};
