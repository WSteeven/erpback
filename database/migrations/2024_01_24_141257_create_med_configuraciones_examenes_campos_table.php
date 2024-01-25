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
        Schema::create('med_configuraciones_examenes_campos', function (Blueprint $table) {
            $table->id();
            $table->string('campo');
            $table->string('unidad_medida');
            $table->integer('rango_superior');
            $table->integer('rango_inferior');
            $table->unsignedBigInteger('configuracion_examen_categoria_id');

            // Foreign keys
            $table->foreign('configuracion_examen_categoria_id')->references('id')->on('med_configuraciones_examenes_categorias')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_configuraciones_examenes_campos');
    }
};
