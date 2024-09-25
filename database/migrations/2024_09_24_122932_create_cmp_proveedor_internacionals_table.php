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
        Schema::create('cmp_proveedores_internacionales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('tipo')->nullable();
            $table->string('ruc')->nullable();
            $table->unsignedBigInteger('pais_id');
            $table->string('direccion')->nullable();
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->string('sitio_web')->nullable();
            $table->boolean('activo')->default(true);
            $table->string('banco1')->nullable();
            $table->string('numero_cuenta1')->nullable();
            $table->string('codigo_swift1')->nullable();
            $table->string('moneda1')->nullable();
            $table->string('banco2')->nullable();
            $table->string('numero_cuenta2')->nullable();
            $table->string('codigo_swift2')->nullable();
            $table->string('moneda2')->nullable();
            $table->timestamps();

            $table->foreign('pais_id')->references('id')->on('paises');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmp_proveedores_internacionales');
    }
};
