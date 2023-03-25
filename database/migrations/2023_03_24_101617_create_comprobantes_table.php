<?php

use App\Models\TransaccionBodega;
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
        Schema::create('comprobantes', function (Blueprint $table) {
            $table->unsignedBigInteger('transaccion_id');
            $table->boolean('firmada')->default(false);
            $table->enum('estado', [TransaccionBodega::PENDIENTE, TransaccionBodega::ACEPTADA, transaccionBodega::RECHAZADA])->default(TransaccionBodega::PENDIENTE);
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->primary('transaccion_id');
            $table->foreign('transaccion_id')->references('id')->on('transacciones_bodega')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comprobantes');
    }
};
