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
        Schema::create('subtareas', function (Blueprint $table) {
            $table->id();

            $table->string('codigo_subtarea');
            $table->text('detalle');
            $table->text('actividad_realizada');
            $table->text('novedades');
            $table->string('fiscalizador');
            $table->string('ing_soporte');
            $table->string('ing_instalacion');
            $table->string('tipo_instalacion');
            $table->string('id_servicio');
            $table->string('ticket_phoenix');

            // Foreign keys
            $table->unsignedBigInteger('tipo_tarea_id');
            $table->foreign('tipo_tarea_id')->references('id')->on('tipos_tareas')->onDelete('cascade')->onUpdate('cascade');

            /* $table->unsignedBigInteger('grupo_id');
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade')->onUpdate('cascade'); */

            $table->unsignedBigInteger('tarea_id');
            $table->foreign('tarea_id')->references('id')->on('tareas')->onDelete('cascade')->onUpdate('cascade');

            /* $table->unsignedBigInteger('ubicacion_origen_id');
            $table->foreign('ubicacion_origen_id')->references('id')->on('ubicaciones_subtareas')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('ubicacion_destino_id');
            $table->foreign('ubicacion_destino_id')->references('id')->on('ubicaciones_subtareas')->onDelete('cascade')->onUpdate('cascade'); */

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
