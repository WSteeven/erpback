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
        Schema::create('plazo_prestamo_empresarial', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('num_cuota');
            $table->date('fecha_vencimiento');
            $table->date('fecha_pago')->nullable();
            $table->decimal('valor_a_pagar');
            $table->unsignedBigInteger('id_prestamo_empresarial');
            $table->foreign('id_prestamo_empresarial')->references('id')->on('prestamo_empresarial');
            $table->boolean('pago_couta');
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
        Schema::dropIfExists('plazo_prestamo_empresarials');
    }
};
