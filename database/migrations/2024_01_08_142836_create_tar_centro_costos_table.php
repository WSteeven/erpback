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
        Schema::create('tar_centros_costos', function (Blueprint $table) {
            $table->id();
            $table->text('nombre');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('cliente_id')->on('clientes')->references('id')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tar_centros_costos');
    }
};
