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
            $table->text('detalle');
            $table->timestamp('fecha_hora_creacion')->nullable();
            $table->timestamp('fecha_hora_asignacion')->nullable();
            $table->timestamp('fecha_hora_ejecucion')->nullable();
            // $table->timestamp('fecha_hora_finalizacion')->nullable();
            $table->integer('cantidad_dias')->nullable();
            $table->timestamp('fecha_hora_realizado')->nullable();
            $table->timestamp('fecha_hora_suspendido')->nullable();
            $table->string('causa_suspencion')->nullable();
            $table->timestamp('fecha_hora_cancelacion')->nullable();
            $table->string('causa_cancelacion')->nullable();
            $table->boolean('es_dependiente')->default(false);

            $table->boolean('es_ventana')->default(false);
            $table->date('fecha_ventana')->nullable();
            $table->string('hora_inicio_ventana')->nullable();
            $table->string('hora_fin_ventana')->nullable();

            $table->text('descripcion_completa')->nullable();
            $table->string('tecnicos_grupo_principal'); // ids historico por si los técnicos se cambian de grupo o se van
            $table->string('tecnicos_otros_grupos')->nullable();
            $table->enum('estado', [Subtarea::ASIGNADO, Subtarea::CANCELADO, Subtarea::CREADO, Subtarea::EJECUTANDO, Subtarea::PAUSADO, Subtarea::REALIZADO, Subtarea::SUSPENDIDO]);

            $table->unsignedBigInteger('subtarea_dependiente')->nullable();

            // Foreign keys
            $table->unsignedBigInteger('tipo_trabajo_id');
            $table->foreign('tipo_trabajo_id')->references('id')->on('tipos_trabajos')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('grupo_id');
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('tarea_id');
            $table->foreign('tarea_id')->references('id')->on('tareas')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('coordinador_id');
            $table->foreign('coordinador_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');

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
