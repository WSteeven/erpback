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
        Schema::create('seg_restricciones_prendas_zonas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('detalle_producto_id')->constrained('detalles_productos')->onDelete('cascade');
            $table->foreignId('miembro_zona_id')->constrained('seg_miembros_zonas')->onDelete('cascade');
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
        Schema::dropIfExists('seg_restriccion_prenda_zonas');
    }
};
