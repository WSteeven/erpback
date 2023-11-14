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
        Schema::create('item_detalle_preingreso_material', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('preingreso_id');
            $table->unsignedBigInteger('detalle_id');
            $table->string('descripcion');
            $table->string('serial')->nullable();
            $table->integer('cantidad');
            $table->integer('punta_inicial')->nullable();
            $table->integer('punta_final')->nullable();
            $table->integer('unidad_medida_id');
            $table->string('fotografia')->nullable();
            $table->timestamps();

            $table->foreign('preingreso_id')->references('id')->on('preingresos_materiales')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('detalle_id')->references('id')->on('detalles_productos')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('item_detalle_preingreso_material');
    }
};
