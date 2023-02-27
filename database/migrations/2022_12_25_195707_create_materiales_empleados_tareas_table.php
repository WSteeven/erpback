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
        Schema::create('materiales_empleados_tareas', function (Blueprint $table) {
            $table->id();

            $table->integer('cantidad_stock'); // Cantidad de material que tiene el empleado a su disposición para la tarea
            $table->boolean('es_fibra')->default(false);

            // Foreign key
            $table->unsignedBigInteger('tarea_id');
            $table->foreign('tarea_id')->references('id')->on('tareas')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');

            // pendiente de verificar si se deja
            /* $table->unsignedBigInteger('grupo_id');
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete('cascade')->onUpdate('cascade'); */

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
        Schema::dropIfExists('materiales_empleados_tareas');
    }
};
