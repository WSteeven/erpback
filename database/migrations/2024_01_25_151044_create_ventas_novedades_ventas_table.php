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
        Schema::create('ventas_novedades_ventas', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha_hora');
            $table->string('actividad');
            $table->string('observacion')->nullable();
            $table->string('fotografia')->nullable();
            $table->unsignedBigInteger('venta_id')->nullable();
            $table->timestamps();

            $table->foreign('venta_id')->references('id')->on('ventas_ventas')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_novedades_ventas');
    }
};
