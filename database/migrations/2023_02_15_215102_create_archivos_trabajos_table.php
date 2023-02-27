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
        Schema::create('archivos_trabajos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('ruta');
            $table->string('tamanio_bytes');
            $table->text('comentario')->nullable();

            // Foreign key
            $table->unsignedBigInteger('trabajo_id');
            $table->foreign('trabajo_id')->references('id')->on('trabajos')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('archivos_trabajos');
    }
};
