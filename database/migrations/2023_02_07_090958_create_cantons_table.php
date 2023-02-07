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
        Schema::create('canton', function (Blueprint $table) {
            $table->integer('id')->increment();
            $table->string('codigo_prov', 4);
            $table->string('codigo_can', 4);
            $table->string('descripcion', 250);
            $table->string('transcriptor', 150);
            $table->timestamp('fecha_trans');
            $table->integer('parroquia_id');
            $table->foreign('parroquia_id')->references('id')->on('parroquia');
            $table->foreign('codigo_prov')->references('codigo_prov')->on('provincia');
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
        Schema::dropIfExists('canton');
    }
};
