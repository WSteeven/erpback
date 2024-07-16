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
        Schema::table('empleados', function (Blueprint $table) {
            $table->string('autoidentificacion_etnica')->nullable();
            $table->boolean('trabajador_sustituto')->default(false);

            $table->unsignedBigInteger('orientacion_sexual_id')->nullable();
            $table->foreign('orientacion_sexual_id')->references('id')->on('med_orientaciones_sexuales');

            $table->unsignedBigInteger('identidad_genero_id')->nullable();
            $table->foreign('identidad_genero_id')->references('id')->on('med_identidades_generos');

            $table->unsignedBigInteger('religion_id')->nullable();
            $table->foreign('religion_id')->references('id')->on('med_religiones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empleados', function (Blueprint $table) {
        });
    }
};
