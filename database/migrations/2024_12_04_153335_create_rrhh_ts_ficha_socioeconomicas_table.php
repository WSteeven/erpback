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
        Schema::create('rrhh_ts_fichas_socioeconomicas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('canton_id');
            $table->string('lugar_nacimiento');
            $table->string('contacto_emergencia')->nullable();
            $table->string('parentesco_contacto_emergencia')->nullable();
            $table->string('telefono_contacto_emergencia')->nullable();
            $table->string('problemas_ambiente_social_familiar')->nullable();
            $table->text('observaciones_ambiente_social_familiar')->nullable();
            $table->text('conocimientos')->nullable();
            $table->text('capacitaciones')->nullable();
            $table->text('imagen_rutagrama')->nullable();
            $table->text('vias_transito_regular_trabajo')->nullable();
            $table->text('conclusiones')->nullable();
            $table->timestamps();

            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('canton_id')->references('id')->on('cantones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_ts_fichas_socioeconomicas');
    }
};
