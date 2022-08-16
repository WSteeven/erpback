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
        Schema::create('codigo_cliente', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('propietario_id')->required();
            $table->unsignedBigInteger('producto_id')->required();
            $table->string('codigo')->required();
            $table->timestamps();

            
            $table->foreign('propietario_id')->references('id')->on('propietarios')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codigo_cliente');
    }
};
