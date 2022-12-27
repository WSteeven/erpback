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
        Schema::create('registro_tendidos', function (Blueprint $table) {
            $table->id();

            $table->string('tipo_elemento');
            $table->string('propietario_elemento');
            $table->integer('numero_elemento');
            $table->string('codigo_elemento');
            $table->integer('progresiva_entrada');
            $table->integer('progresiva_salida');
            $table->double('coordenada_del_elemento_latitud');
            $table->double('coordenada_del_elemento_longitud');
            $table->double('coordenada_cruce_americano_longitud');
            $table->double('coordenada_cruce_americano_latitud');
            $table->double('coordenada_poste_anclaje1_longitud');
            $table->double('coordenada_poste_anclaje1_latitud');
            $table->double('coordenada_poste_anclaje2_longitud');
            $table->double('coordenada_poste_anclaje2_latitud');
            $table->string('estado_elemento');
            $table->boolean('tiene_transformador')->default(false);
            $table->integer('cantidad_transformadores');
            $table->boolean('tiene_americano')->default(false);
            $table->boolean('tiene_retenidas')->default(false);
            $table->integer('cantidad_retenidas');
            $table->boolean('instalo_manga')->default(false);
            $table->boolean('instalo_reserva')->default(false);
            $table->integer('cantidad_reservas');
            $table->text('observaciones');
            $table->string('imagen');
            // $table->string('listadoProductosSeleccionados');

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
        Schema::dropIfExists('registro_tendidos');
    }
};
