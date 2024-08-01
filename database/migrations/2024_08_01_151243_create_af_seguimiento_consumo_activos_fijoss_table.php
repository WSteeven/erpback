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
        Schema::create('af_seguimientos_consumo_activos_fijos', function (Blueprint $table) {
            $table->id();

            $table->integer('stock_actual');
            $table->integer('cantidad_utilizada');

            // Foreign key
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('detalle_producto_id');
            $table->foreign('detalle_producto_id', 'fk_detalle_prod')->references('id')->on('detalles_productos')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->foreign('cliente_id')->references('id')->on('clientes');

            $table->unsignedBigInteger('motivo_consumo_activo_fijo_id')->nullable();
            $table->foreign('motivo_consumo_activo_fijo_id', 'fk_motivo_consumo_af_id')->references('id')->on('af_motivos_consumo_activos_fijos')->cascadeOnUpdate()->nullOnDelete();

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
        Schema::dropIfExists('af_seguimientos_consumo_activos_fijos');
    }
};
