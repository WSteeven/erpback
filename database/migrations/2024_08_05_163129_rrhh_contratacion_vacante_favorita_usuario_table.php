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
        Schema::create('rrhh_contratacion_vacante_favorita_usuario', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('vacante_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type');
            $table->timestamps();

            $table->foreign('vacante_id', 'fk_vacante_favorita_user')->references('id')->on('rrhh_contratacion_vacantes')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_contratacion_vacante_favorita_usuario');
    }
};
