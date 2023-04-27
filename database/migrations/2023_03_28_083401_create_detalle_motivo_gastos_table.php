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
        Schema::create('detalle_motivo_gastos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_motivo_gasto');
            $table->unsignedBigInteger('id_gasto_coordinador');
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
        Schema::dropIfExists('detalle_motivo_gastos');
    }
};
