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
        Schema::create('rrhh_nomina_vacacions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('periodo_id');
            $table->integer('dias')->default(15);
            $table->boolean('opto_pago')->default(false);
            $table->boolean('completadas')->default(false);
            $table->timestamps();

            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('periodo_id')->references('id')->on('periodos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_nomina_vacacions');
    }
};
