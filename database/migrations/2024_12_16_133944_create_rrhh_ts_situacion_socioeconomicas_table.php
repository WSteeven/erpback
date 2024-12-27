<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh_ts_situaciones_socioeconomicas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ficha_id');
            $table->unsignedBigInteger('empleado_id');
            $table->integer('cantidad_personas_aportan')->default(0);
            $table->integer('cantidad_personas_dependientes')->default(0);
            $table->boolean('recibe_apoyo_economico_otro_familiar');
            $table->string('familiar_apoya_economicamente')->nullable();
            $table->boolean('recibe_apoyo_economico_gobierno');
            $table->string('institucion_apoya_economicamente')->nullable();
            $table->boolean('tiene_prestamos');
            $table->integer('cantidad_prestamos')->default(0);
            $table->string('entidad_bancaria')->nullable();
            $table->boolean('tiene_tarjeta_credito');
            $table->integer('cantidad_tarjetas_credito')->default(0);
            $table->string('vehiculo');
            $table->boolean('tiene_terreno');
            $table->boolean('tiene_bienes');
            $table->boolean('tiene_ingresos_adicionales');
            $table->decimal('ingresos_adicionales')->default(0);
            $table->boolean('apoya_familiar_externo');
            $table->string('familiar_externo_apoyado')->nullable();
            $table->timestamps();

            $table->foreign('ficha_id')->references('id')->on('rrhh_ts_fichas_socioeconomicas');
            $table->foreign('empleado_id')->references('id')->on('empleados');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_ts_situaciones_socioeconomicas');
    }
};
