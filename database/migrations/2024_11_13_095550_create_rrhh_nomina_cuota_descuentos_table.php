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
        Schema::create('rrhh_nomina_cuotas_descuentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('descuento_id');
            $table->integer('num_cuota');
            $table->string('mes_vencimiento');
            $table->decimal('valor_cuota');
            $table->boolean('pagada')->default(false);
            $table->text('comentario')->nullable();
            $table->timestamps();

            $table->foreign('descuento_id')->references('id')->on('rrhh_nomina_descuentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_nomina_cuotas_descuentos');
    }
};
