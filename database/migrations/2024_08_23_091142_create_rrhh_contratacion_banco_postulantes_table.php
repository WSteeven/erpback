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
        Schema::create('rrhh_contratacion_bancos_postulantes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_type');
            $table->unsignedBigInteger('cargo_id');
            $table->unsignedBigInteger('postulacion_id');
            $table->string('puntuacion')->nullable();
            $table->text('observacion')->nullable();
            $table->boolean('descartado')->default(false);
            $table->integer('fue_contactado')->default(0);
            $table->timestamps();

            $table->foreign('cargo_id', 'fk_cargo_banco')->references('id')->on('cargos')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('postulacion_id', 'fk_postulacion_banco')->references('id')->on('rrhh_contratacion_postulaciones')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_contratacion_bancos_postulantes');
    }
};
