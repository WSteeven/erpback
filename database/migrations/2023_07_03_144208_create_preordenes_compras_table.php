<?php

use App\Models\EstadoTransaccion;
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
        Schema::create('cmp_preordenes_compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitante_id')->nullable();
            $table->unsignedBigInteger('pedido_id')->nullable();
            $table->unsignedBigInteger('autorizador_id')->nullable();
            $table->unsignedBigInteger('autorizacion_id')->nullable();
            $table->enum('estado', [EstadoTransaccion::PENDIENTE, EstadoTransaccion::COMPLETA, EstadoTransaccion::ANULADA])->default(EstadoTransaccion::PENDIENTE);
            $table->timestamps();

            $table->foreign('solicitante_id')->references('id')->on('empleados')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('pedido_id')->references('id')->on('pedidos')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('autorizador_id')->references('id')->on('empleados')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('autorizacion_id')->references('id')->on('autorizaciones')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmp_preordenes_compras');
    }
};
