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
            $table->unsignedBigInteger('transf_produc_emplea_id');
            $table->integer('cantidad');
            $table->unsignedBigInteger('cliente_id');

            $table->foreign('detalle_producto_id')->references('id')->on('detalles_productos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('transf_produc_emplea_id')->references('id')->on('tar_transf_produc_emplea')->onUpdate('cascade')->onDelete('cascade');

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
