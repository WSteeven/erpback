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
        Schema::create('ventas_chargebacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('venta_id');
            $table->date('fecha');
            $table->decimal('valor', 8, 4);
            $table->unsignedBigInteger('id_tipo_chargeback');
            $table->decimal('porcentaje', 8, 4)->nullable();
            $table->timestamps();
            $table->foreign('venta_id')->references('id')->on('ventas_ventas')->cascadeOnUpdate();
            $table->foreign('id_tipo_chargeback')->references('id')->on('ventas_tipo_chargeback')->cascadeOnUpdate();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_chargebacks');
    }
};
