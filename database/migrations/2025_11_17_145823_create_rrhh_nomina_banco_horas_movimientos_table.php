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
        Schema::create('rrhh_nomina_banco_horas_movimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->string('tipo');
            $table->decimal('horas');
            $table->timestamp('fecha_movimiento');
            $table->text('detalle');
            $table->boolean('descontado')->default(false);
            $table->unsignedBigInteger('model_id');
            $table->text('model_type');
            $table->timestamps();

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
        Schema::dropIfExists('rrhh_nomina_banco_horas_movimientos');
    }
};
