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
        Schema::create('rrhh_contratacion_vacantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->longText('descripcion');
            $table->date('fecha_caducidad');
            $table->string('imagen_referencia');
            $table->string('imagen_publicidad');
            $table->string('anios_experiencia')->nullable();
            $table->text('areas_conocimiento')->nullable();
            $table->integer('numero_postulantes')->default(0);
            $table->unsignedBigInteger('tipo_puesto_id');
            $table->unsignedBigInteger('modalidad_id');
            $table->unsignedBigInteger('publicante_id');
            $table->unsignedBigInteger('solicitud_id')->nullable();
            $table->unsignedBigInteger('canton_id')->nullable();
            $table->integer('num_plazas')->default(1);
            $table->boolean('disponibilidad_viajar')->default(false);
            $table->boolean('requiere_licencia')->default(false);
            $table->boolean('activo')->default(true);
            $table->boolean('es_completada')->default(false);
            $table->timestamps();

            //Laves foraneas
            $table->foreign('tipo_puesto_id', 'fk_vacante_tipo_puesto')->references('id')->on('rrhh_contratacion_tipos_puestos')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('publicante_id', 'fk_publicante_id')->references('id')->on('empleados')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('solicitud_id', 'fk_vacante_autorizacion_id')->references('id')->on('rrhh_contratacion_solicitudes_nuevas_vacantes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('modalidad_id', 'fk_vacante_modalidad')->references('id')->on('rrhh_contratacion_modalidades')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('canton_id', 'fk_canton_vacante')->references('id')->on('cantones')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_contratacion_vacantes');
    }
};
