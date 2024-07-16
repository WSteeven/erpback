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
        Schema::create('veh_planes_mantenimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehiculo_id');
            $table->unsignedBigInteger('servicio_id');
            $table->integer('aplicar_desde');
            $table->integer('aplicar_cada');
            $table->integer('notificar_antes')->nullable();
            $table->text('datos_adicionales')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['vehiculo_id', 'servicio_id']);
            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('servicio_id')->references('id')->on('veh_servicios')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veh_planes_mantenimientos');
    }
};
