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
        Schema::create('veh_ordenes_reparaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitante_id')->nullable();
            $table->unsignedBigInteger('vehiculo_id')->nullable();
            $table->unsignedBigInteger('autorizador_id')->nullable();
            $table->unsignedBigInteger('autorizacion_id')->nullable();
            $table->text('servicios')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->foreign('solicitante_id')->references('id')->on('empleados')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('autorizador_id')->references('id')->on('empleados')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('autorizacion_id')->references('id')->on('autorizaciones')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veh_ordenes_reparaciones');
    }
};
