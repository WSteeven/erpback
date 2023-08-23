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
        Schema::create('extension_cobertura_salud', function (Blueprint $table) {
            $table->id();
            $table->string('mes',7);
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->unsignedBigInteger('dependiente');
            $table->foreign('dependiente')->references('id')->on('familiares');
            $table->string('origen');
            $table->decimal('materia_grabada', 10, 2);
            $table->decimal('aporte', 10, 2);
            $table->decimal('aporte_porcentaje', 10, 2);
            $table->boolean('aprobado');
            $table->string('observacion');
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
        Schema::dropIfExists('extension_cobertura_salud');
    }
};
