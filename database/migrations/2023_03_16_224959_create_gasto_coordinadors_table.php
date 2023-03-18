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
        Schema::create('gastos_coordinador', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_gasto');
            $table->unsignedBigInteger('id_lugar');
            $table->unsignedBigInteger('id_motivo');
            $table->double('monto');
            $table->text('observacion');
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_motivo')->references('id')->on('motivo_gastos');
            $table->foreign('id_lugar')->references('id')->on('cantones');
            $table->foreign('id_usuario')->references('id')->on('users');
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
        Schema::dropIfExists('gastos_coordinador');
    }
};
