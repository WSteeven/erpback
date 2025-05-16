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
        Schema::create('veh_licencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('conductor_id');
            $table->string('tipo_licencia');
            $table->date('inicio_vigencia');
            $table->date('fin_vigencia');
            $table->timestamps();

            $table->unique(['conductor_id', 'tipo_licencia']);
            $table->foreign('conductor_id')->references('empleado_id')->on('veh_conductores')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veh_licencias');
    }
};
