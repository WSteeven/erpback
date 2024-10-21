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
        Schema::create('tar_alimentacion_grupos', function (Blueprint $table) {
            $table->id();

            $table->string('observacion')->nullable();
            $table->integer('cantidad_personas');
            $table->float('precio');
            $table->date('fecha');

            // Foreign keys
            $table->unsignedBigInteger('tarea_id');
            $table->foreign('tarea_id')->references('id')->on('tareas')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('subtarea_id')->nullable();
            $table->foreign('subtarea_id')->references('id')->on('subtareas')->nullOnDelete()->nullOnUpdate();

            $table->unsignedBigInteger('grupo_id')->nullable();
            $table->foreign('grupo_id')->references('id')->on('grupos')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('tipo_alimentacion_id')->nullable();
            $table->foreign('tipo_alimentacion_id')->references('id')->on('sub_detalle_viatico')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('tar_alimentacion_grupos');
    }
};
