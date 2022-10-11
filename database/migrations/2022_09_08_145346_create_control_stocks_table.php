<?php

use App\Models\ControlStock;
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
        Schema::create('control_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detalle_id');
            $table->unsignedBigInteger('sucursal_id');
            $table->unsignedBigInteger('cliente_id');
            $table->integer('minimo')->nullable();
            $table->integer('reorden')->nullable();
            $table->enum('estado', [ControlStock::SUFICIENTE, ControlStock::REORDEN, ControlStock::MINIMO])->default(ControlStock::SUFICIENTE);
            $table->timestamps();

            $table->unique(['detalle_id', 'sucursal_id', 'cliente_id']);
            $table->foreign('detalle_id')->references('id')->on('detalles_productos');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');
            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('control_stocks');
    }
};
