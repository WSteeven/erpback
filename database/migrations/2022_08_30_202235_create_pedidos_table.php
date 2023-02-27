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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->text('justificacion');
            $table->date('fecha_limite')->nullable();
            $table->text('observacion_aut')->nullable();
            $table->text('observacion_est')->nullable();
            $table->unsignedBigInteger('solicitante_id')->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->unsignedBigInteger('autorizacion_id')->nullable();
            $table->unsignedBigInteger('per_autoriza_id')->nullable();
            $table->unsignedBigInteger('tarea_id')->nullable();
            $table->unsignedBigInteger('sucursal_id')->nullable();
            $table->unsignedBigInteger('estado_id')->nullable();
            $table->timestamps();

            $table->foreign('solicitante_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('responsable_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('per_autoriza_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('autorizacion_id')->references('id')->on('autorizaciones')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('tarea_id')->references('id')->on('tareas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('sucursal_id')->references('id')->on('sucursales')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('estado_id')->references('id')->on('estados_transacciones_bodega')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
};
