<?php

use App\Models\Subtarea;
use App\Models\Tarea;
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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_tarea');
            $table->string('codigo_tarea_cliente')->nullable();
            $table->string('fecha_solicitud')->nullable();
            $table->string('titulo');
            // $table->string('detalle');
            $table->enum('para_cliente_proyecto', [Tarea::PARA_PROYECTO, Tarea::PARA_CLIENTE_FINAL]);
            // $table->enum('estado', [Subtarea::ASIGNADO, Subtarea::CANCELADO, Subtarea::CREADO, Subtarea::EJECUTANDO, Subtarea::PAUSADO, Subtarea::REALIZADO, Subtarea::SUSPENDIDO]);
            //$table->text('observacion')->nullable();
            //$table->boolean('tiene_subtareas');

            // Foreign keys
            $table->unsignedBigInteger('cliente_id')->nullable(); // cliente principal
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('cliente_final_id')->nullable();
            $table->foreign('cliente_final_id')->references('id')->on('clientes_finales')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('fiscalizador_id')->nullable();
            $table->foreign('fiscalizador_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('coordinador_id');
            $table->foreign('coordinador_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('proyecto_id')->nullable();
            $table->foreign('proyecto_id')->references('id')->on('proyectos')->onDelete('set null')->onUpdate('cascade');

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
        Schema::dropIfExists('tareas');
    }
};
