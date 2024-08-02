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
        Schema::create('rrhh_contratacion_solicitudes_nuevas_vacantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('publicada')->default(false);
            $table->unsignedBigInteger('tipo_puesto_id');
            $table->unsignedBigInteger('solicitante_id');
            $table->unsignedBigInteger('modalidad_id');
            $table->unsignedBigInteger('autorizador_id');
            $table->unsignedBigInteger('autorizacion_id');
            $table->unsignedBigInteger('cargo_id')->nullable();
            $table->text('areas_conocimiento')->nullable();
            $table->longText('descripcion');
            $table->string('anios_experiencia')->nullable();
            $table->boolean('disponibilidad_viajar')->default(false);
            $table->boolean('requiere_licencia')->default(false);

            $table->timestamps();

            //Laves foraneas
            $table->foreign('tipo_puesto_id', 'fk_tipo_puesto')->references('id')->on('rrhh_contratacion_tipos_puestos')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('modalidad_id', 'fk_modalidad')->references('id')->on('rrhh_contratacion_modalidades')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('cargo_id')->references('id')->on('cargos')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('solicitante_id', 'fk_solicitante_id')->references('id')->on('empleados')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('autorizador_id', 'fk_autorizador_id')->references('id')->on('empleados')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('autorizacion_id', 'fk_autorizacion_id')->references('id')->on('autorizaciones')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_contratacion_solicitudes_nuevas_vacantes');
    }
};
