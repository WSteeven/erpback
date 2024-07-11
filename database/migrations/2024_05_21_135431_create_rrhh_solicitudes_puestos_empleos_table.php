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
        Schema::create('rrhh_solicitudes_puestos_empleos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('publicada')->default(false);
            $table->unsignedBigInteger('tipo_puesto_id');
            $table->unsignedBigInteger('autorizador_id');
            $table->unsignedBigInteger('autorizacion_id');
            $table->unsignedBigInteger('cargo_id')->nullable();
            $table->longText('descripcion');
            $table->string('anios_experiencia')->nullable();

            $table->timestamps();

            //Laves foraneas
            $table->foreign('tipo_puesto_id')->references('id')->on('rrhh_tipos_puestos_trabajos')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('cargo_id')->references('id')->on('cargos')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('autorizador_id')->references('id')->on('empleados')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('autorizacion_id')->references('id')->on('autorizaciones')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_solicitudes_puestos_empleos');
    }
};
