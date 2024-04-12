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
        Schema::create('veh_bitacoras_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('hora_salida');
            $table->string('hora_llegada')->nullable();
            $table->double('km_inicial', 9, 2, true);
            $table->double('km_final', 9, 2, true)->nullable();
            $table->unsignedInteger('tanque_inicio');
            $table->unsignedInteger('tanque_final')->nullable();
            $table->boolean('firmada')->default(false);
            $table->unsignedBigInteger('chofer_id');
            $table->unsignedBigInteger('vehiculo_id');
            $table->timestamps();

            $table->foreign('chofer_id')->references('id')->on('empleados')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veh_bitacoras_vehiculos');
    }
};
