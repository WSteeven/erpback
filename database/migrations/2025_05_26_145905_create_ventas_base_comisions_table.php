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
        Schema::create('ventas_bases_comisiones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modalidad_id')->unique();
            $table->integer('presupuesto_ventas');
            $table->integer('presupuesto_referidos')->nullable();
            $table->decimal('bono_comision_semanal')->nullable();
            $table->json('comisiones');
            $table->timestamps();

            $table->foreign('modalidad_id')->references('id')->on('ventas_modalidades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_bases_comisiones');
    }
};
