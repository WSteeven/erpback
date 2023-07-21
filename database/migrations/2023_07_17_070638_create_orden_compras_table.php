<?php

use App\Models\OrdenCompra;
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
        Schema::create('ordenes_compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitante_id')->nullable();
            $table->unsignedBigInteger('proveedor_id')->nullable();
            $table->unsignedBigInteger('autorizador_id')->nullable();
            $table->unsignedBigInteger('autorizacion_id')->nullable();
            $table->text('observacion_aut')->nullable();
            $table->unsignedBigInteger('estado_id')->nullable();
            $table->text('observacion_est')->nullable();
            $table->text('descripcion');
            $table->enum('forma', [OrdenCompra::CONTADO, OrdenCompra::CREDITO])->default(OrdenCompra::CONTADO)->nullable();
            $table->enum('tiempo', [OrdenCompra::SEMANAL, OrdenCompra::QUINCENAL, OrdenCompra::MES])->nullable();
            $table->unsignedBigInteger('fecha');
            $table->string('categorias');
            $table->timestamps(); 

            $table->foreign('solicitante_id')->references('id')->on('empleados')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('autorizador_id')->references('id')->on('empleados')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('proveedor_id')->references('id')->on('proveedores')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('autorizacion_id')->references('id')->on('autorizaciones')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('estado_id')->references('id')->on('estados_transacciones_bodega')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordenes_compras');
    }
};
