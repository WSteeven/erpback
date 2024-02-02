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
        Schema::create('ventas_retenciones_chargebacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venta_id')->nullable();
            $table->unsignedBigInteger('vendedor_id')->nullable();
            $table->timestamp('fecha_retencion');
            $table->double('valor_retenido');
            $table->boolean('pagado')->default(false);
            $table->timestamps();

            $table->foreign('venta_id')->references('id')->on('ventas_ventas')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('vendedor_id')->references('empleado_id')->on('ventas_vendedores')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_retenciones_chargebacks');
    }
};
