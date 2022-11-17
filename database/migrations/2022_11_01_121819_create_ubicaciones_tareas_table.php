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
        Schema::create('ubicaciones_tareas', function (Blueprint $table) {
            $table->id();
            
            $table->string('parroquia');
            $table->string('direccion');
            $table->string('referencias');
            $table->string('coordenadas');
            
            $table->unsignedBigInteger('provincia_id');
            $table->foreign('provincia_id')->references('id')->on('provincias');
            
            $table->unsignedBigInteger('canton_id');
            $table->foreign('canton_id')->references('id')->on('cantones');

            // $table->unsignedBigInteger('tarea_id')->unique();
            // $table->foreign('tarea_id')->references('id')->on('tareas');

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
        Schema::dropIfExists('ubicaciones_tareas');
    }
};
