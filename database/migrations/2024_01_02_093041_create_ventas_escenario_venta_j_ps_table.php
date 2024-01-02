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
        Schema::create('ventas_escenario_ventas_jp', function (Blueprint $table) {
            $table->id();
            $table->integer('mes');
            $table->integer('apoyo_das_fijos');
            $table->integer('vendedores');
            $table->integer('productividad_minima');
            $table->integer('vendedores_acumulados');
            $table->decimal('total_ventas_adicionales');
            $table->decimal('arpu_prom');
            $table->integer('altas');
            $table->integer('bajas');
            $table->integer('neta');
            $table->integer('stock');
            $table->integer('stock_que_factura');
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
        Schema::dropIfExists('ventas_escenario_venta_j_ps');
    }
};
