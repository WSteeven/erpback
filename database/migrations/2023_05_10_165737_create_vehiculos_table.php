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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa')->required()->unique();
            $table->string('num_chasis')->unique()->required();
            $table->string('num_motor')->unique()->required();
            $table->unsignedInteger('anio_fabricacion');
            $table->unsignedInteger('cilindraje');
            $table->unsignedInteger('rendimiento');
            $table->unsignedBigInteger('modelo_id');
            $table->unsignedBigInteger('combustible_id');
            $table->timestamps();

            $table->foreign('modelo_id')->references('id')->on('modelos')->onUpdate('cascade')->onDelete(null);
            $table->foreign('combustible_id')->references('id')->on('combustibles')->onUpdate('cascade')->onDelete(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehiculos');
    }
};
