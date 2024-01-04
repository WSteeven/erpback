<?php

use App\Models\Autorizacion;
use App\Models\Tareas\TransferenciaProductoEmpleado;
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
        Schema::create('tar_transf_produc_emplea', function (Blueprint $table) {
            $table->id();
            $table->text('justificacion');
            $table->text('causa_anulacion')->nullable();
            $table->enum('estado', [TransferenciaProductoEmpleado::PENDIENTE, TransferenciaProductoEmpleado::COMPLETA, TransferenciaProductoEmpleado::ANULADA])->default(TransferenciaProductoEmpleado::PENDIENTE);
            $table->text('observacion_aut')->nullable();
            $table->unsignedBigInteger('solicitante_id');
            $table->unsignedBigInteger('empleado_origen_id');
            $table->unsignedBigInteger('empleado_destino_id');
            $table->unsignedBigInteger('proyecto_origen_id')->nullable();
            $table->unsignedBigInteger('proyecto_destino_id')->nullable();
            $table->unsignedBigInteger('etapa_origen_id')->nullable();
            $table->unsignedBigInteger('etapa_destino_id')->nullable();
            $table->unsignedBigInteger('tarea_origen_id')->nullable();
            $table->unsignedBigInteger('tarea_destino_id')->nullable();
            $table->unsignedBigInteger('autorizacion_id');
            $table->unsignedBigInteger('autorizador_id');
            $table->unsignedBigInteger('cliente_id');

            $table->foreign('solicitante_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('empleado_origen_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('empleado_destino_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('proyecto_origen_id')->references('id')->on('proyectos')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('proyecto_destino_id')->references('id')->on('proyectos')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('etapa_origen_id')->references('id')->on('tar_etapas')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('etapa_destino_id')->references('id')->on('tar_etapas')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('tarea_origen_id')->references('id')->on('tareas')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('tarea_destino_id')->references('id')->on('tareas')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('autorizador_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('autorizacion_id')->references('id')->on('autorizaciones')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tar_transferencias_productos_empleados');
    }
};
