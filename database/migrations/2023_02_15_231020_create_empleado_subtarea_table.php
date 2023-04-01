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
        Schema::create('empleado_subtarea', function (Blueprint $table) {
            $table->id();

            // Normalmente es el lider del grupo pero puede darse el caso de que se designe a un miembro del mismo grupo para que lo suplante de manera temporal entonces aqui puede ir su id de empleado
            // En estos casos el reemplazo temporal tiene la capacidad de utilizar el material que se ha asignado al lider oficial
            $table->boolean('es_responsable');

            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('subtarea_id');
            $table->foreign('subtarea_id')->references('id')->on('subtareas')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('empleado_subtarea');
    }
};
