<?php

use App\Models\Subtarea;
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
        Schema::create('subtareas', function (Blueprint $table) {
            $table->id();

            $table->string('codigo_subtarea');
            $table->text('titulo');
            $table->text('descripcion_completa')->nullable();
            $table->text('observacion')->nullable();

            $table->enum('estado', [Subtarea::AGENDADO, Subtarea::ASIGNADO, Subtarea::CANCELADO, Subtarea::CREADO, Subtarea::EJECUTANDO, Subtarea::PAUSADO, Subtarea::REALIZADO, Subtarea::SUSPENDIDO, Subtarea::FINALIZADO]);
            $table->enum('modo_asignacion_trabajo', [Subtarea::POR_GRUPO, Subtarea::POR_EMPLEADO]);

            $table->timestamp('fecha_hora_creacion')->nullable();
            $table->timestamp('fecha_hora_asignacion')->nullable(); // Torre a coordinador
            $table->timestamp('fecha_hora_agendado')->nullable();   // Coordinador a técnico
            $table->timestamp('fecha_hora_ejecucion')->nullable();
            $table->timestamp('fecha_hora_realizado')->nullable();
            $table->timestamp('fecha_hora_finalizacion')->nullable();
            $table->timestamp('fecha_hora_suspendido')->nullable();
            // $table->string('causa_suspencion')->nullable();
            $table->timestamp('fecha_hora_cancelado')->nullable();
            // $table->string('causa_cancelacion')->nullable();

            $table->boolean('es_dependiente')->default(false);
            $table->boolean('es_ventana')->default(false);

            $table->date('fecha_inicio_trabajo')->nullable();
            $table->string('hora_inicio_trabajo')->nullable();
            $table->string('hora_fin_trabajo')->nullable();

            // Foreign keys
            $table->unsignedBigInteger('motivo_cancelado_id')->nullable();
            $table->foreign('motivo_cancelado_id')->references('id')->on('motivos_suspendidos')->onDelete('set null')->onUpdate('cascade');

            // $table->unsignedBigInteger('motivo_suspendido_id')->nullable();
            // $table->foreign('motivo_suspendido_id')->references('id')->on('motivos_suspendidos')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedBigInteger('subtarea_dependiente_id')->nullable();

            $table->unsignedBigInteger('tipo_trabajo_id');
            $table->foreign('tipo_trabajo_id')->references('id')->on('tipos_trabajos')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('tarea_id');
            $table->foreign('tarea_id')->references('id')->on('tareas')->onDelete('cascade')->onUpdate('cascade');

            // Se asigna un trabajo a un grupo de empleados, generalmente técnicos
            $table->unsignedBigInteger('grupo_id')->nullable();
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('set null')->onUpdate('cascade');

            // Se asigna un trabajo únicamente para un empleado
            $table->unsignedBigInteger('empleado_id')->nullable();
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('set null')->onUpdate('cascade');

            /* $table->unsignedBigInteger('coordinador_id');
            $table->foreign('coordinador_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');*/

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
        Schema::dropIfExists('subtareas');
    }
};
