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
        Schema::create('rrhh_empleado_tipo_discapacidad_porcentaje', function (Blueprint $table) {
            $table->id();
            $table->double('porcentaje');

            $table->unsignedBigInteger('tipo_discapacidad_id');
            $table->foreign('tipo_discapacidad_id', 'fk_tip_discp')->references('id')->on('rrhh_tipos_discapacidades')->cascadeOnUpdate();

            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados')->cascadeOnUpdate();


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
        Schema::dropIfExists('rrhh_empleado_tipo_discapacidad_porcentaje');
    }
};
