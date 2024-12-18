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
        Schema::create('rrhh_ts_visitas_domiciliarias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('canton_id');
            $table->string('lugar_nacimiento');
            $table->string('contacto_emergencia')->nullable();
            $table->string('parentesco_contacto_emergencia')->nullable();
            $table->string('telefono_contacto_emergencia')->nullable();
            $table->text('diagnostico_social');
            $table->text('imagen_genograma')->nullable();
            $table->text('imagen_visita_domiciliaria')->nullable();
            $table->text('observaciones');
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
        Schema::dropIfExists('rrhh_ts_visitas_domiciliarias');
    }
};
