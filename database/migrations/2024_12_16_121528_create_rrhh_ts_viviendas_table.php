<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh_ts_viviendas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->string('tipo');
            $table->string('material_paredes');
            $table->string('material_techo');
            $table->string('material_piso');
            $table->string('distribucion_vivienda');
            $table->string('comodidad_espacio_familiar');
            $table->integer('numero_dormitorios');
            $table->boolean('existe_hacinamiento');
            $table->boolean('existe_upc_cercano');
            $table->string('otras_consideraciones')->nullable();
            $table->text('imagen_croquis');
            $table->string('telefono');
            $table->string('coordenadas');
            $table->string('direccion');
            $table->string('referencia');
            $table->string('servicios_basicos');
            $table->unsignedBigInteger('model_id');
            $table->text('model_type');

            $table->timestamps();

            $table->foreign('empleado_id')->references('id')->on('empleados');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_ts_viviendas');
    }
};
