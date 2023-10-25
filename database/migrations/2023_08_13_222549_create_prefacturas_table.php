<?php

use App\Models\ComprasProveedores\OrdenCompra;
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
        Schema::create('cmp_prefacturas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->unsignedBigInteger('solicitante_id')->nullable();
            $table->unsignedBigInteger('proforma_id')->nullable();
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->unsignedBigInteger('estado_id')->nullable();
            $table->text('causa_anulacion')->nullable();
            $table->text('descripcion');
            $table->enum('forma', [OrdenCompra::CONTADO, OrdenCompra::CREDITO])->default(OrdenCompra::CONTADO)->nullable();
            $table->enum('tiempo', [OrdenCompra::SEMANAL, OrdenCompra::QUINCENAL, OrdenCompra::MES])->nullable();
            $table->double('iva')->default(12.0);
            $table->timestamps();

            $table->foreign('solicitante_id')->references('id')->on('empleados')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('cliente_id')->references('id')->on('clientes')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('estado_id')->references('id')->on('estados_transacciones_bodega')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('proforma_id')->references('id')->on('cmp_proformas')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmp_prefacturas');
    }
};
