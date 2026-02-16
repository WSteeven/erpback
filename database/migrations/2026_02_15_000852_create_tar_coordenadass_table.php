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
        Schema::create('tar_coordenadas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subtarea_id');
            $table->unsignedBigInteger('tipo_id')->nullable();
            $table->string('nombre');
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->string('direccion')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->foreign('subtarea_id')->references('id')->on('subtareas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tipo_id')->references('id')->on('tar_tipos_coordenadas')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tar_coordenadas');
    }
};
