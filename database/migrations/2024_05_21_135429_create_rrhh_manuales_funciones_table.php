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
        Schema::create('rrhh_manuales_funciones', function (Blueprint $table) {
            $table->id();
            $table->string('objetivo_cargo');
            $table->text('procesos_relacionados');
            $table->text('posicion_organigrama_general');
            $table->text('responsabilidades_cargo');
            $table->text('funciones_especificas');
            $table->text('autoridad_toma_decisiones');
            $table->text('relaciones_trabajo');
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
        Schema::dropIfExists('rrhh_manuales_funciones');
    }
};
