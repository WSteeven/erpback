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

            $table->string('id_cliente_final');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('celular');
            $table->string('parroquia');
            $table->string('direccion');
            $table->string('referencia');
            $table->string('coordenada_latitud')->nullable();
            $table->string('coordenada_longitud')->nullable();
            
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
