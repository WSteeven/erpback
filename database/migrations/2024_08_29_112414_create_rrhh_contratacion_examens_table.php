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
        Schema::create('rrhh_contratacion_examenes', function (Blueprint $table) {
            $table->unsignedBigInteger('postulacion_id');
            $table->timestamp('fecha_hora');
            $table->unsignedBigInteger('canton_id')->nullable();
            $table->text('direccion')->nullable();
            $table->text('laboratorio')->nullable();
            $table->text('indicaciones')->nullable();
            $table->boolean('se_realizo_examen')->default(false);
            $table->boolean('es_apto')->default(false);
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->primary('postulacion_id');
            $table->foreign('postulacion_id')->references('id')->on('rrhh_contratacion_postulaciones')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('canton_id')->references('id')->on('cantones')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_contratacion_examenes');
    }
};
