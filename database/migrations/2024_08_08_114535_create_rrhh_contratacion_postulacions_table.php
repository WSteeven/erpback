<?php

use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
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
        Schema::create('rrhh_contratacion_postulaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); //polymorphic, not configure foreign key
            $table->string('user_type');
            $table->unsignedBigInteger('vacante_id');
            $table->unsignedBigInteger('pais_residencia_id');
            $table->text('direccion');
            $table->longText('mi_experiencia')->nullable();
            $table->boolean('tengo_conocimientos_requeridos')->default(false);
            $table->boolean('tengo_disponibilidad_viajar')->default(false);
            $table->boolean('tengo_documentos_regla')->default(false);
            $table->boolean('tengo_experiencia_requerida')->default(false);
            $table->boolean('tengo_formacion_academica_requerida')->default(false);
            $table->boolean('tengo_licencia_conducir')->default(false);
            $table->string('tipo_licencia')->nullable();
            $table->string('estado')->default(Postulacion::POSTULADO);
            $table->text('ruta_cv')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('vacante_id')->references('id')->on('rrhh_contratacion_vacantes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('pais_residencia_id')->references('id')->on('paises')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_contratacion_postulaciones');
    }
};
