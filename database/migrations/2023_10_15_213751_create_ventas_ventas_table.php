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
        Schema::create('ventas_ventas', function (Blueprint $table) {
            $table->id();
            $table->string('orden_id');
            $table->string('orden_interna');
            $table->unsignedBigInteger('vendedor_id');
            $table->unsignedBigInteger('producto_id');
            $table->date('fecha_activ')->nullable();
            $table->string('estado_activ');
            $table->string('forma_pago');
            $table->unsignedBigInteger('comision_id');
            $table->decimal('chargeback',8,4);
            $table->decimal('comision_vendedor',8,4);
            $table->timestamps();
            $table->foreign('vendedor_id', 'fk_vendedor_ventas_id')->references('id')->on('ventas_vendedor')->cascadeOnUpdate();
            $table->foreign('producto_id', 'fk_vendedor_ventas_producto_ventas_id')->references('id')->on('ventas_producto_ventas')->cascadeOnUpdate();
            $table->foreign('comision_id', 'fk_comision_id')->references('id')->on('ventas_comisiones')->cascadeOnUpdate();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_ventas');
    }
};
