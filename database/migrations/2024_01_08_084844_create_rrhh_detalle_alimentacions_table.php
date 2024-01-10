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
        Schema::create('rrhh_detalle_alimentaciones', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor_asignado');
            $table->date('fecha_corte');
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('alimentacion_id');
            $table->foreign('empleado_id')->on('empleados')->references('id');
            $table->foreign('alimentacion_id')->on('rrhh_alimentaciones')->references('id');
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
        Schema::dropIfExists('rrhh_detalle_alimentacions');
    }
};
