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
        Schema::create('cmp_detalles_proformas', function (Blueprint $table) {
            $table->id();
            $table->text('nombre');
            $table->unsignedBigInteger('unidad_medida_id')->nullable();
            $table->double('precio_unitario')->default(0.00);
            $table->boolean('facturable')->default(true);
            $table->boolean('grava_iva')->default(true);
            $table->boolean('estado')->default(true);
            $table->timestamps();

            $table->foreign('unidad_medida_id')->references('id')->on('unidades_medidas')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmp_detalles_proformas');
    }
};
