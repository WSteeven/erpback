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
        Schema::create('archivos_seguimientos', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('ruta');
            $table->string('tamanio_bytes');

            // Foreign key
            $table->unsignedBigInteger('seguimiento_id');
            $table->foreign('seguimiento_id')->references('id')->on('seguimientos')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('archivos_seguimientos');
    }
};
