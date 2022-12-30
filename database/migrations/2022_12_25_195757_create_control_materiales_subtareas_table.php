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
        Schema::create('control_materiales_subtareas', function (Blueprint $table) {
            $table->id();

            $table->integer('stock_actual');
            $table->integer('cantidad_utilizada');
            $table->string('fecha');

            // Foreign key
            $table->unsignedBigInteger('tarea_id');
            $table->foreign('tarea_id')->references('id')->on('tareas')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('subtarea_id');
            $table->foreign('subtarea_id')->references('id')->on('subtareas')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('grupo_id');
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('detalle_producto_id');
            $table->foreign('detalle_producto_id')->references('id')->on('detalles_productos')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('control_materiales_subtareas');
    }
};
