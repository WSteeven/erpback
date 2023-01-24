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
            $table->id();
            // $table->unsignedBigInteger('id');
            $table->text('justificacion');
            $table->unsignedBigInteger('sucursal_salida_id');
            $table->unsignedBigInteger('sucursal_destino_id');
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('solicitante_id');
            $table->unsignedBigInteger('autorizacion_id');
            $table->unsignedBigInteger('per_autoriza_id');
            $table->boolean('recibida')->default(false);
            // $table->boolean('recibida')->default(false);
            $table->enum('estado', [Transferencia::PENDIENTE, Transferencia::TRANSITO, Transferencia::COMPLETADO])->default(Transferencia::PENDIENTE);
            $table->text('observacion_aut');
            $table->text('observacion_est');
            // $table->boolean('devuelta')->default(false);
            $table->timestamps();

            $table->foreign('sucursal_salida_id')->references('id')->on('sucursales')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sucursal_destino_id')->references('id')->on('sucursales')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('autorizacion_id')->references('id')->on('autorizaciones')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('per_autoriza_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('solicitante_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade')->onUpdate('cascade');

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
