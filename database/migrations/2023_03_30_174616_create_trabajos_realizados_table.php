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
        Schema::create('trabajos_realizados', function (Blueprint $table) {
            $table->id();

            $table->string('trabajo_realizado');
            $table->string('fotografia')->nullable();

            // Foreign keys
            $table->unsignedBigInteger('seguimiento_id');
            $table->foreign('seguimiento_id')->references('id')->on('emergencias')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('trabajos_realizados');
    }
};
