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
        Schema::create('rrhh_contratacion_evaluaciones_personalidades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); //polymorphic, not configure foreign key
            $table->string('user_type');
            $table->unsignedBigInteger('postulacion_id')->nullable();
            $table->json('respuestas');
            $table->timestamp('fecha_realizacion');
            $table->boolean('completado')->default(false);
            $table->timestamps();

            $table->foreign('postulacion_id', 'fk_postulacion')->references('id')->on('rrhh_contratacion_postulaciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_contratacion_evaluaciones_personalidades');
    }
};
