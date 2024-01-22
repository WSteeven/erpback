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
        Schema::create('ventas_umbrales_ventas', function (Blueprint $table) {
            $table->id();
            $table->integer('cantidad_ventas')->defaultValue(0);
            $table->unsignedBigInteger('vendedor_id')->nullable();
            $table->timestamps();

            $table->foreign('vendedor_id')->references('empleado_id')->on('ventas_vendedores')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_umbrales_ventas');
    }
};
