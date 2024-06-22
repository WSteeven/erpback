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
        Schema::create('veh_multas_conductores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id')->nullable();
            $table->timestamp('fecha_infraccion');
            $table->timestamp('fecha_pago')->nullable();
            $table->string('comentario')->nullable();
            $table->string('placa')->nullable();
            $table->double('puntos', 6, 2)->nullable();
            $table->double('total', 6, 2);
            $table->boolean('estado')->default(false);
            $table->boolean('descontable')->default(true);
            $table->timestamps();

            $table->foreign('empleado_id')->references('empleado_id')->on('veh_conductores')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veh_multas_conductores');
    }
};
