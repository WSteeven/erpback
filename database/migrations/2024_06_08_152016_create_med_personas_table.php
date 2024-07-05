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
        Schema::create('med_personas', function (Blueprint $table) {
            $table->id();

            $table->string('primer_nombre');
            $table->string('segundo_nombre');
            $table->string('primer_apellido');
            $table->string('segundo_apellido');
            $table->string('area')->nullable();
            $table->string('nivel_academico')->nullable();
            $table->string('antiguedad')->nullable();
            $table->string('correo')->nullable();
            $table->string('genero')->nullable();
            $table->string('nombre_empresa')->nullable();
            $table->string('ruc')->nullable();
            $table->string('cargo')->nullable();
            $table->string('identificacion')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('tipo_afiliacion_seguridad_social')->nullable();
            $table->string('nivel_instruccion')->nullable();
            $table->integer('numero_hijos')->default(0);
            $table->string('autoidentificacion_etnica')->nullable();
            $table->text('porcentaje_discapacidad')->nullable();
            $table->boolean('es_trabajador_sustituto')->default(false);
            $table->string('enfermedades_preexistentes')->nullable();
            $table->boolean('ha_recibido_capacitacion')->default(true);
            $table->boolean('tiene_examen_preocupacional')->default(true);

            // Foreign keys
            $table->unsignedBigInteger('estado_civil_id');
            $table->foreign('estado_civil_id')->on('estado_civil')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('provincia_id');
            $table->foreign('provincia_id')->on('provincias')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('canton_id');
            $table->foreign('canton_id')->on('cantones')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('tipo_cuestionario_id');
            $table->foreign('tipo_cuestionario_id')->on('med_tipos_cuestionarios')->references('id')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_personas');
    }
};
