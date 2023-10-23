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
        Schema::create('med_resultados_examenes', function (Blueprint $table) {
            $table->id();
            $table->json('resultados');
            $table->string('url_certificado');

            // Foreign keys
            $table->unsignedBigInteger('empleado_examen_id');
            $table->foreign('empleado_examen_id')->references('id')->on('med_empleado_examen')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('med_resultados_examenes');
    }
};
