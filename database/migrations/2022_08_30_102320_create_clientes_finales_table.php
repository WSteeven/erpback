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
            $table->string('apellidos')->nullable();
            $table->string('celular')->nullable();
            $table->string('parroquia')->nullable();
            $table->string('direccion')->nullable();
            $table->string('referencia')->nullable();
            $table->integer('coordenada_latitud')->nullable();
            $table->integer('coordenada_longitud')->nullable();

            $table->unsignedBigInteger('provincia_id')->nullable();
            $table->foreign('provincia_id')->references('id')->on('provincias')->onUpdate('set null')->onDelete('cascade');

            $table->unsignedBigInteger('canton_id')->nullable();
            $table->foreign('canton_id')->references('id')->on('cantones')->onUpdate('set null')->onDelete('cascade');

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
