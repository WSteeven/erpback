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
        Schema::create('detalle_inventario_transferencia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transferencia_id');
            $table->unsignedBigInteger('inventario_id');
            $table->integer('cantidad');
            $table->timestamps();

            $table->foreign('transferencia_id')->references('id')->on('transferencias')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('inventario_id')->references('id')->on('inventarios')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_inventario_transferencia');
    }
};
