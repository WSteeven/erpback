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
        Schema::create('rrhh_ts_experiencias_previas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ficha_id');
            $table->unsignedBigInteger('empleado_id');
            $table->string('nombre_empresa');
            $table->string('cargo');
            $table->string('antiguedad');
            $table->boolean('asegurado_iess');
            $table->string('telefono');
            $table->date('fecha_retiro');
            $table->string('motivo_retiro');
            $table->timestamps();

             $table->foreign('ficha_id')->references('id')->on('rrhh_ts_fichas_socioeconomicas');
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
        Schema::dropIfExists('rrhh_ts_experiencias_previas');
    }
};
