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
        Schema::create('bitacora_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('hora_salida');
            $table->string('hora_llegada');
            $table->double('km_inicial',9,2,true);
            $table->double('km_final',9,2,true);
            $table->unsignedInteger('tanque_inicio');
            $table->unsignedInteger('tanque_final');
            $table->boolean('firmada')->default(false);
            $table->unsignedBigInteger('chofer_id');
            $table->unsignedBigInteger('vehiculo_id');
            $table->timestamps();

            $table->foreign('chofer_id')->references('id')->on('empleados')->onUpdate('cascade')->onDelete(null);
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->onUpdate('cascade')->onDelete(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bitacora_vehiculos');
    }
};
