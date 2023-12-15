<?php

use App\Models\Tareas\TransferenciaMaterialEmpleado;
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
        Schema::create('tar_transferencias_materiales_empleados', function (Blueprint $table) {
            $table->id();
            $table->text('justificacion');
            $table->unsignedBigInteger('solicitante_id');
            $table->unsignedBigInteger('tarea_id')->nullable();
            $table->unsignedBigInteger('etapa_id')->nullable();
            $table->unsignedBigInteger('proyecto_id')->nullable();
            $table->unsignedBigInteger('canton_id');
            $table->boolean('stock_personal')->default(false);
            $table->text('causa_anulacion')->nullable();
            $table->enum('estado', [TransferenciaMaterialEmpleado::PENDIENTE, TransferenciaMaterialEmpleado::COMPLETA, TransferenciaMaterialEmpleado::ANULADA])->default(TransferenciaMaterialEmpleado::PENDIENTE);

            $table->timestamps();

            $table->foreign('solicitante_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tarea_id')->references('id')->on('tareas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('etapa_id')->references('id')->on('tar_etapas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('proyecto_id')->references('id')->on('proyectos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('canton_id')->references('id')->on('cantones')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tar_transferencias_materiales_empleados');
    }
};
