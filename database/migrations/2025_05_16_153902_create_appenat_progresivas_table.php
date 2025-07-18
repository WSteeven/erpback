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
        Schema::create('appenate_progresivas', function (Blueprint $table) {
            $table->id();
            $table->json('metadatos');
            $table->string('filename');
            $table->string('proyecto');
            $table->string('ciudad');
            $table->string('enlace')->nullable();
            $table->timestamp('fecha_instalacion');
            $table->string('cod_bobina')->nullable();
            $table->integer('mt_inicial')->nullable();
            $table->integer('mt_final')->nullable();
            $table->integer('fo_instalada')->nullable();
            $table->string('num_tarea')->nullable();
            $table->string('hilos')->nullable();
            $table->string('responsable');
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
        Schema::dropIfExists('appenate_progresivas');
    }
};
