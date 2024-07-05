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
        Schema::create('veh_conductores', function (Blueprint $table) {
            $table->unsignedBigInteger('empleado_id');
            $table->string('tipo_licencia');
            $table->date('inicio_vigencia');
            $table->date('fin_vigencia');
            $table->double('puntos');
            $table->timestamps();

            $table->primary('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veh_conductores');
    }
};
