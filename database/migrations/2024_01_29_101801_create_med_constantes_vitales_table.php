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
            $table->decimal('saturacion_oxigeno');
            $table->integer('frecuencia_respiratoria');
            $table->decimal('peso',8,2);
            $table->decimal('talla',8,2);
            $table->decimal('indice_masa_corporal',8,2);
            $table->decimal('perimetro_abdominal',8,2);
            $table->unsignedBigInteger('constante_vitalable_id');
            $table->string('constante_vitalable_type');
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
