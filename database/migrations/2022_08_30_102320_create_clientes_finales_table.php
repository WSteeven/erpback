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
        Schema::create('clientes_finales', function (Blueprint $table) {
            $table->id();

            $table->string('id_cliente');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('celular');
            $table->string('parroquia');
            $table->string('direccion');
            $table->string('referencias');
            $table->string('coordenadas');
            
            $table->unsignedBigInteger('provincia_id');
            $table->foreign('provincia_id')->references('id')->on('provincias');
            
            $table->unsignedBigInteger('canton_id');
            $table->foreign('canton_id')->references('id')->on('cantones');

            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes');

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
        Schema::dropIfExists('clientes_finales');
    }
};
