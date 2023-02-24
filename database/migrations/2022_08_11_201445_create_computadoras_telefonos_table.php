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
        Schema::create('computadoras_telefonos', function (Blueprint $table) {
            $table->string('imei')->nullable();
            $table->unsignedBigInteger('detalle_id');
            $table->unsignedBigInteger('memoria_id');
            $table->unsignedBigInteger('disco_id');
            $table->unsignedBigInteger('procesador_id');
            $table->timestamps();

            $table->primary('detalle_id');
            $table->foreign('detalle_id')->references('id')->on('detalles_productos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('memoria_id')->references('id')->on('rams')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('disco_id')->references('id')->on('discos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('procesador_id')->references('id')->on('procesadores')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('computadoras_telefonos');
    }
};
