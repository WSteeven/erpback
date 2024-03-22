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
        Schema::create('cmp_pagos_proveedores', function (Blueprint $table) {
            $table->id();
            $table->text('nombre');
            $table->unsignedBigInteger('realizador_id')->nullable();
            $table->boolean('estado_bloqueado')->default(true);
            $table->timestamps();

            $table->foreign('realizador_id')->on('empleados')->references('id')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmp_pagos_proveedores');
    }
};
