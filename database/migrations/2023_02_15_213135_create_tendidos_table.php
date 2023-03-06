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
        Schema::create('tendidos', function (Blueprint $table) {
            $table->id();

            $table->integer('marca_inicial')->nullable();
            $table->integer('marca_final')->nullable();

            // Foreign key
            $table->unsignedBigInteger('subtarea_id');
            $table->foreign('subtarea_id')->references('id')->on('subtareas')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('bobina_id');
            $table->foreign('bobina_id')->references('id')->on('detalles_productos')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('tendidos');
    }
};
