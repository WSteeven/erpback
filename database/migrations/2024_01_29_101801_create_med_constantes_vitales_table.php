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
        Schema::create('med_constantes_vitales', function (Blueprint $table) {
            $table->id();
            $table->string('presion_arterial');
            $table->decimal('temperatura',8,2);
            $table->integer('frecuencia_cardiaca');
            $table->integer('saturacion_oxigeno');
            $table->integer('frecuencia_respiratoria');
            $table->decimal('peso',8,2);
            $table->decimal('estatura',8,2);
            $table->decimal('talla',8,2);
            $table->decimal('indice_masa_corporal',8,2);
            $table->decimal('perimetro_abdominal',8,2);
            $table->unsignedBigInteger('ficha_preocupacional_id');
            $table->foreign('ficha_preocupacional_id')->on('med_preocupacionales')->references('id')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('med_constantes_vitales');
    }
};
