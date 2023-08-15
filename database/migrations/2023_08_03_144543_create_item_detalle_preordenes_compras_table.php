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
        Schema::create('cmp_item_detalle_preorden_compra', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detalle_id');
            $table->unsignedBigInteger('preorden_id');
            $table->integer('cantidad');
            $table->timestamps();

            $table->foreign('detalle_id')->references('id')->on('detalles_productos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('preorden_id')->references('id')->on('cmp_preordenes_compras')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmp_item_detalle_preorden_compra');
    }
};
