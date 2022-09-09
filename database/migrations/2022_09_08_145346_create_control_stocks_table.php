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
            $table->integer('minimo')->nullable();
            $table->integer('reorden')->nullable();
            $table->enum('estado', [ControlStock::SUFICIENTE, ControlStock::REORDEN, ControlStock::MINIMO]);
            $table->timestamps();

            $table->unique('detalle_id', 'sucursal_id');
            $table->foreign('detalle_id')->references('id')->on('detalles_productos');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');
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
