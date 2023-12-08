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
        Schema::create('cmp_logisticas_proveedores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->string('tiempo_entrega')->nullable();
            $table->boolean('envios')->default(true);
            $table->string('tipo_envio')->nullable(); //Local, provincial, nacional
            $table->boolean('transporte_incluido')->default(true);
            $table->string('costo_transporte')->nullable(); //Costos para los distintos lugares de envio: local, provincial, nacional
            $table->boolean('garantia')->default(true);
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmp_logisticas_proveedores');
    }
};
