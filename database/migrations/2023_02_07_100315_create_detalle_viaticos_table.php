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
        Schema::create('detalle_viatico', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion', 250);
            $table->string('autorizacion', 2);
            $table->unsignedBigInteger('id_estatus');
            $table->string('transcriptor', 120);
            $table->timestamp('fecha_trans');
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
        Schema::dropIfExists('detalle_viatico');
    }
};
