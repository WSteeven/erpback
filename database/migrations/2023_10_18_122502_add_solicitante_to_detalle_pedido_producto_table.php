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
        Schema::table('detalle_pedido_producto', function (Blueprint $table) {
            $table->unsignedBigInteger('solicitante_id')->nullable()->after('pedido_id');

            $table->foreign('solicitante_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detalle_pedido_producto', function (Blueprint $table) {
            //
        });
    }
};
