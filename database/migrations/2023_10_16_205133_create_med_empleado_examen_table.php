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
        Schema::create('med_empleado_examen', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('estado_examen_id');
            $table->foreign('estado_examen_id')->references('id')->on('med_estados_examenes')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('registro_examen_id');
            $table->foreign('registro_examen_id')->references('id')->on('med_registros_examenes')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('examen_id');
            $table->foreign('examen_id')->references('id')->on('med_examenes')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('med_empleado_examen');
    }
};
