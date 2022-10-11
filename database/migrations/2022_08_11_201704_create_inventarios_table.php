<?php

use App\Models\Inventario;
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
        Schema::create('inventarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detalle_id');
            $table->unsignedBigInteger('sucursal_id');
            $table->unsignedBigInteger('cliente_id');
            $table->integer('cantidad')->default(0);
            $table->integer('prestados')->default(0);
            $table->unsignedBigInteger('condicion_id');
            $table->enum('estado', [Inventario::INVENTARIO, Inventario::TRANSITO, Inventario::SIN_STOCK])->default(Inventario::INVENTARIO);
            $table->timestamps();

            $table->unique(['detalle_id', 'sucursal_id', 'cliente_id']);
            $table->foreign('detalle_id')->references('id')->on('detalles_productos');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');
            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('condicion_id')->references('id')->on('condiciones_de_productos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventarios');
    }
};
