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
        Schema::create('ubicaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->unsignedBigInteger('percha_id');
            $table->unsignedBigInteger('piso_id')->nullable();
            $table->timestamps();

            $table->foreign('percha_id')->references('id')->on('perchas')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('piso_id')->references('id')->on('pisos')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ubicaciones');
    }
};
