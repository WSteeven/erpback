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
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad');
            $table->double('precio_unitario');
            $table->integer('saldo');
            $table->unsignedBigInteger('bodeguero_id');
            $table->unsignedBigInteger('inventario_id');
            
            $table->unsignedBigInteger('movimientable_id');
            $table->string('movimientable_type');

            $table->foreign('bodeguero_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('movimientos');
    }
};
