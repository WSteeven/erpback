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
        Schema::create('rrhh_postulantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('tipo_documento_identificacion');
            $table->string('numero_documento_identificacion');
            $table->string('telefono');
            $table->string('correo_personal')->nullable();
            $table->string('direccion')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('genero')->nullable();
            $table->unsignedBigInteger('identidad_genero_id')->nullable();
            $table->unsignedBigInteger('pais_id')->nullable();

            $table->timestamps();

            ///Llaves foraneas
            $table->unsignedBigInteger('usuario_external_id');
            $table->foreign('usuario_external_id')->on('rrhh_users_externals')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('identidad_genero_id')->on('med_identidades_generos')->references('id')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('pais_id')->on('paises')->references('id')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_postulantes');
    }
};
