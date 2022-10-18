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
        Schema::create('fibras', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('detalle_id');
            $table->unsignedBigInteger('span_id');
            $table->unsignedBigInteger('tipo_fibra_id');
            $table->unsignedBigInteger('hilo_id');
            $table->integer('punta_inicial');
            $table->integer('punta_final');
            $table->integer('custodia');
            $table->timestamps();

            $table->primary(['detalle_id']);
            $table->foreign('detalle_id')->references('id')->on('detalles_productos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('span_id')->references('id')->on('spans')->onDelete(null)->onUpdate('cascade');
            $table->foreign('tipo_fibra_id')->references('id')->on('tipo_fibras')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('hilo_id')->references('id')->on('hilos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fibras');
    }
};
