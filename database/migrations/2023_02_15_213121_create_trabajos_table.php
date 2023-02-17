<?php

use App\Models\Trabajo;
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
        Schema::create('trabajos', function (Blueprint $table) {
            $table->id();

            $table->string('codigo_trabajo');
            $table->string('codigo_trabajo_cliente');
            $table->text('titulo');
            $table->text('descripcion_completa')->nullable();
            $table->text('observacion')->nullable();
            $table->enum('para_cliente_proyecto', [Trabajo::PARA_PROYECTO, Trabajo::PARA_CLIENTE_FINAL]);
            $table->string('fecha_solicitud')->nullable();
            $table->enum('estado', [Trabajo::ASIGNADO, Trabajo::CANCELADO, Trabajo::CREADO, Trabajo::EJECUTANDO, Trabajo::PAUSADO, Trabajo::REALIZADO, Trabajo::SUSPENDIDO, Trabajo::FINALIZADO]);
            $table->enum('modo_asignacion_trabajo', [Trabajo::POR_GRUPO, Trabajo::POR_EMPLEADO]);

            $table->timestamp('fecha_hora_creacion')->nullable();
            $table->timestamp('fecha_hora_asignacion')->nullable();
            $table->timestamp('fecha_hora_ejecucion')->nullable();
            $table->timestamp('fecha_hora_realizado')->nullable(); // tecnico
            $table->timestamp('fecha_hora_finalizacion')->nullable(); // coordinador
            $table->timestamp('fecha_hora_suspendido')->nullable();
            $table->string('causa_suspencion')->nullable();
            $table->timestamp('fecha_hora_cancelacion')->nullable();
            $table->string('causa_cancelacion')->nullable();

            $table->boolean('es_dependiente')->default(false);
            $table->boolean('es_ventana')->default(false);
            $table->boolean('tiene_subtrabajos');

            $table->string('fecha_agendado')->nullable();
            $table->string('hora_inicio_agendado')->nullable();
            $table->string('hora_fin_agendado')->nullable();

            // Foreign keys
            $table->unsignedBigInteger('tipo_trabajo_id');
            $table->foreign('tipo_trabajo_id')->references('id')->on('tipos_trabajos')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('cliente_final_id')->nullable();
            $table->foreign('cliente_final_id')->references('id')->on('clientes_finales')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('coordinador_id');
            $table->foreign('coordinador_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('fiscalizador_id')->nullable();
            $table->foreign('fiscalizador_id')->references('id')->on('empleados')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedBigInteger('proyecto_id')->nullable();
            $table->foreign('proyecto_id')->references('id')->on('proyectos')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedBigInteger('cliente_id')->nullable(); // cliente principal
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('trabajo_padre_id')->nullable();
            $table->unsignedBigInteger('trabajo_dependiente_id')->nullable();

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
        Schema::dropIfExists('trabajos');
    }
};
