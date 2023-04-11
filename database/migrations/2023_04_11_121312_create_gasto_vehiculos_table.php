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
        Schema::create('gasto_vehiculos', function (Blueprint $table) {
            $table->unsignedBigInteger('id_gasto');
            $table->string('placa', 10);
            $table->integer('kilometraje');
            $table->primary(['id_gasto']);
            $table->foreign('id_gasto')->references('id')->on('gastos');
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
        Schema::dropIfExists('gasto_vehiculos');
    }
};
