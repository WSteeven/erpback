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
        Schema::table('devoluciones', function (Blueprint $table) {
            $table->unsignedBigInteger('canton_id')->nullable()->change();
            $table->unsignedBigInteger('sucursal_id')->nullable()->after('canton_id');
            $table->boolean('pedido_automatico')->default(false)->after('sucursal_id');

            $table->foreign('sucursal_id')->references('id')->on('sucursales')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devoluciones', function (Blueprint $table) {
            //
        });
    }
};
