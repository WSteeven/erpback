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
        Schema::create('prestamos_temporales', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_salida');
            $table->date('fecha_devolucion');
            $table->string('observacion')->nullable();
            $table->unsignedBigInteger('solicitante_id')->required();
            $table->unsignedBigInteger('per_entrega_id')->required();
            $table->unsignedBigInteger('per_recibe_id')->nullable();
            $table->timestamps();

            $table->foreign('solicitante_id')->references('id')->on('empleados');
            $table->foreign('per_entrega_id')->references('id')->on('empleados');
            $table->foreign('per_recibe_id')->references('id')->on('empleados');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prestamos_temporales');
    }
};
