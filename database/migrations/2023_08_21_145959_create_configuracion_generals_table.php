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
        Schema::create('configuraciones_generales', function (Blueprint $table) {
            $table->id();
            $table->text('logo_claro');
            $table->text('logo_oscuro');
            $table->text('logo_marca_agua');
            $table->string('ruc');
            $table->string('representante')->nullable();
            $table->text('razon_social')->nullable();
            $table->text('nombre_comercial')->nullable();
            $table->text('direccion_principal')->nullable();
            $table->text('telefono')->nullable();
            $table->string('moneda')->default('$');
            $table->string('tipo_contribuyente')->nullable();
            $table->text('celular1')->nullable();
            $table->text('celular2')->nullable();
            $table->string('correo_principal')->nullable();
            $table->string('correo_secundario')->nullable();
            $table->string('sitio_web')->nullable();
            $table->text('direccion_secundaria1')->nullable();
            $table->text('direccion_secundaria2')->nullable();
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
        Schema::dropIfExists('configuraciones_generales');
    }
};
