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
        Schema::create('ventas_bonos_mensuales_cumplimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendedor_id')->nullable();
            $table->integer('cant_ventas');
            $table->string('mes', 7);
            $table->decimal('valor', 8, 4);
            $table->boolean('pagada')->default(false);
            $table->string('bonificable_type')->nullable();
            $table->unsignedBigInteger('bonificable_id')->nullable();
            $table->timestamps();

            $table->unique(['vendedor_id', 'mes']);

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
        Schema::dropIfExists('ventas_bonos_mensuales_cumplimientos');
    }
};
