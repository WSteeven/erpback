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
        Schema::create('codigo_cliente', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id')->required()->default(1);
            $table->text('nombre_cliente')->nullable();
            $table->unsignedBigInteger('detalle_id')->required();
            $table->string('codigo')->unique()->required();
            $table->timestamps();


            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('detalle_id')->references('id')->on('detalles_productos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codigo_cliente');
    }
};
