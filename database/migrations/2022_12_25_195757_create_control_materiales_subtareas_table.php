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

            $table->integer('cantidad_actual');
            $table->integer('cantidad_usada');

            // Foreign key
            $table->unsignedBigInteger('subtarea_id');
            $table->foreign('subtarea_id')->references('id')->on('subtareas')->onDelete('cascade')->onUpdate('cascade');

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
