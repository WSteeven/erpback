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
        Schema::create('appenate_registros_progresivas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('progresiva_id');
            $table->string('num_elemento');
            $table->string('propietario');
            $table->string('elemento');
            $table->string('tipo_poste');
            $table->string('material_poste');
            $table->string('ubicacion_gps');
            $table->string('foto');
            $table->string('observaciones')->nullable();
            $table->boolean('tiene_control_cambio')->default(false);
            $table->string('observacion_cambio')->nullable();
            $table->string('foto_cambio')->nullable();
            $table->string('hora_cambio')->nullable();
            $table->timestamps();

            $table->foreign('progresiva_id')->references('id')->on('appenate_progresivas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appenate_registros_progresivas');
    }
};
