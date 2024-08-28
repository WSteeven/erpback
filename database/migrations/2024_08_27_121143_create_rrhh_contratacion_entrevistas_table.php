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
        Schema::create('rrhh_contratacion_entrevistas', function (Blueprint $table) {
            $table->unsignedBigInteger('postulacion_id');
            $table->timestamp('fecha_hora');
            $table->integer('duracion');
            $table->string('link')->nullable();
            $table->boolean('presencial')->default(true);
            $table->boolean('reagendada')->default(false);
            $table->timestamp('nueva_fecha_hora')->nullable();
            $table->text('observacion')->nullable();
            $table->boolean('asistio')->default(false);
            $table->timestamps();

            $table->primary('postulacion_id');
            $table->foreign('postulacion_id')->references('id')->on('rrhh_contratacion_postulaciones')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_contratacion_entrevistas');
    }
};
