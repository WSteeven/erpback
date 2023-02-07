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
        Schema::create('rol_permiso', function (Blueprint $table) {
            $table->integer('id')->increment();
            $table->integer('id_rol');
            $table->integer('id_permiso');
            $table->string('transcriptor',120);
            $table->timestamp('fecha_trans');
            $table->foreign('id_rol')->references('id')->on('rol');
            $table->foreign('id_permiso')->references('id')->on('permiso');
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
        Schema::dropIfExists('rol_permiso');
    }
};
