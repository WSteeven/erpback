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
        Schema::create('veh_tanqueos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('vehiculo_id');
            $table->unsignedBigInteger('solicitante_id');
            $table->timestamp('fecha_hora');
            $table->integer('km_tanqueo');
            $table->string('imagen_comprobante')->nullable();
            $table->string('imagen_tablero')->nullable();

            $table->timestamps();

            $table->foreign('solicitante_id')->references('id')->on('empleados')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veh_tanqueos');
    }
};
