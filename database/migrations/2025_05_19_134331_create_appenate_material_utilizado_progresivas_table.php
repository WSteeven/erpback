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
        Schema::create('appenate_materiales_utilizados_progresivas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registro_id');
            $table->string('material');
            $table->integer('cantidad');
            $table->timestamps();

            $table->foreign('registro_id')->references('id')->on('appenate_registros_progresivas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appenate_materiales_utilizados_progresivas');
    }
};
