<?php

use App\Models\Transferencia;
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
        Schema::create('transferencias', function (Blueprint $table) {
            // $table->id();
            $table->unsignedBigInteger('transaccion_id');
            $table->unsignedBigInteger('sucursal_destino_id');
            $table->boolean('atendida')->default(false);
            $table->boolean('recibida')->default(false);
            $table->enum('estado', [Transferencia::PENDIENTE, Transferencia::TRANSITO, Transferencia::COMPLETADO])->default(Transferencia::PENDIENTE);
            $table->boolean('devuelta')->default(false);
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
        Schema::dropIfExists('transferencias');
    }
};
