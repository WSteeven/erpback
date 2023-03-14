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
        Schema::create('sub_detalle_viatico', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_detalle_viatico');
            $table->string('descripcion', 250);
            $table->string('autorizacion', 2);
            $table->unsignedBigInteger('id_estatus');
            $table->foreign('id_detalle_viatico')->references('id')->on('detalle_viatico');
            $table->foreign('id_estatus')->references('id')->on('estatus');
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
        Schema::dropIfExists('sub_detalle_viatico');
    }
};
