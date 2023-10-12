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
        Schema::create('cmp_novedades_ordenes_compras', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha_hora');
            $table->string('actividad');
            $table->string('observacion')->nullable();
            $table->string('fotografia')->nullable();
            $table->unsignedBigInteger('orden_compra_id')->nullable();
            $table->timestamps();

            $table->foreign('orden_compra_id')->references('id')->on('cmp_ordenes_compras')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmp_novedades_ordenes_compras');
    }
};
