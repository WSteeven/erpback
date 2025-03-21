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
        Schema::table('transacciones_bodega', function (Blueprint $table) {
            // $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade')->after('estado_id')->nullable();
            $table->unsignedBigInteger('proveedor_id')->nullable()->after('estado_id'); // Definir la columna primero
            $table->foreign('proveedor_id')->references('id')->on('proveedores')->onDelete('cascade');
            $table->date('fecha_compra')->after('proveedor_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transacciones_bodega', function (Blueprint $table) {
            $table->dropForeign(['proveedor_id']);
            $table->dropColumn('proveedor_id');
            $table->dropColumn('fecha_compra');
        });
    }
};
