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

            // $table->string('tipo_elemento');
            // $table->string('propietario_elemento');
            $table->string('propietario_americano')->nullable();
            $table->integer('numero_elemento');
            $table->string('codigo_elemento');
            $table->integer('progresiva_entrada');
            $table->integer('progresiva_salida');
            $table->double('coordenada_del_elemento_latitud');
            $table->double('coordenada_del_elemento_longitud');
            $table->double('coordenada_cruce_americano_longitud')->nullable();
            $table->double('coordenada_cruce_americano_latitud')->nullable();
            $table->double('coordenada_poste_anclaje1_longitud')->nullable();
            $table->double('coordenada_poste_anclaje1_latitud')->nullable();
            $table->double('coordenada_poste_anclaje2_longitud')->nullable();
            $table->double('coordenada_poste_anclaje2_latitud')->nullable();
            $table->string('estado_elemento');
            // $table->boolean('tiene_transformador')->default(false);
            $table->integer('cantidad_transformadores')->nullable();
            $table->boolean('tiene_americano')->default(false);
            // $table->boolean('tiene_retenidas')->default(false);
            $table->integer('cantidad_retenidas')->nullable();
            $table->boolean('instalo_manga')->default(false);
            // $table->boolean('instalo_reserva')->default(false);
            $table->integer('cantidad_reserva')->nullable();
            $table->string('tension')->nullable();
            $table->text('observaciones')->nullable();
            $table->json('materiales_ocupados');

            $table->string('imagen_elemento');
            $table->string('imagen_cruce_americano')->nullable();
            $table->string('imagen_poste_anclaje1')->nullable();
            $table->string('imagen_poste_anclaje2')->nullable();

            // Foreign key
            $table->unsignedBigInteger('tendido_id');
            $table->foreign('tendido_id')->references('id')->on('tendidos')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('tipo_elemento_id');
            $table->foreign('tipo_elemento_id')->references('id')->on('tipos_elementos')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('propietario_elemento_id');
            $table->foreign('propietario_elemento_id')->references('id')->on('propietarios_elementos')->onDelete('cascade')->onUpdate('cascade');

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
