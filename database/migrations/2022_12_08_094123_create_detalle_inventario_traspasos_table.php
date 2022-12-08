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
        Schema::create('detalle_inventario_traspaso', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('traspaso_id');
            $table->unsignedBigInteger('inventario_id');
            $table->integer('cantidad');
            $table->timestamps();

            $table->foreign('traspaso_id')->references('id')->on('traspasos')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('inventario_id')->references('id')->on('inventarios')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_inventario_traspaso');
    }
};
