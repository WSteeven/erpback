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
        Schema::create('rrhh_ts_familias_acogientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vivienda_id');
            $table->unsignedBigInteger('canton_id');
            $table->unsignedBigInteger('parroquia_id');
            $table->string('tipo_parroquia');
            $table->string('nombres_apellidos');
            $table->string('direccion');
            $table->string('coordenadas');
            $table->string('telefono');

            $table->timestamps();

            $table->foreign('vivienda_id')->references('id')->on('rrhh_ts_viviendas');
            $table->foreign('canton_id')->references('id')->on('cantones');
            $table->foreign('parroquia_id')->references('id')->on('parroquias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_ts_familias_acogientes');
    }
};
