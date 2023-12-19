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
        Schema::create('tar_det_tran_prod_emp', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('detalle_producto_id');
            $table->unsignedBigInteger('transferencia_producto_empleado_id');
            $table->integer('cantidad');

            $table->foreign('detalle_producto_id')->references('id')->on('detalles_productos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('transferencia_producto_empleado_id')->references('id')->on('tar_transferencias_productos_empleados')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('tar_det_tran_prod_emp');
    }
};
