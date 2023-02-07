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
            $table->integer('id')->increment();
            $table->integer('id_detalle_viatico', 12);
            $table->string('descripcion', 250);
            $table->string('autorizacion', 2);
            $table->integer('id_estatus', 12);
            $table->string('transcriptor', 120);
            $table->timestamp('fecha_trans');
            $table->foreing('id_detalle_viatico')->references('id')->on('detalle_viatico');
            $table->foreing('id_estatus')->references('id')->on('estatus');
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
