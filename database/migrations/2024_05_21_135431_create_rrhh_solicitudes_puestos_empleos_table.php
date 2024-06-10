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
            $table->string('descripcion');
            $table->integer('anos_experiencia');

            //Laves foraneas
            $table->unsignedBigInteger('tipo_puesto_id');
            $table->foreign('tipo_puesto_id')->references('id')->on('rrhh_tipos_puestos_trabajos')->cascadeOnUpdate()->cascadeOnDelete();

            $table->unsignedBigInteger('cargo_id');
            $table->foreign('cargo_id')->references('id')->on('cargos')->cascadeOnUpdate()->cascadeOnDelete();

            $table->unsignedBigInteger('autorizacion_id');
            $table->foreign('autorizacion_id')->references('id')->on('autorizaciones')->cascadeOnUpdate()->cascadeOnDelete();


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
        Schema::dropIfExists('rrhh_solicitudes_puestos_empleos');
    }
};
