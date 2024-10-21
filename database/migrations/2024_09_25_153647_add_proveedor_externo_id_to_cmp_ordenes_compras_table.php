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
        Schema::table('cmp_ordenes_compras', function (Blueprint $table) {
            $table->unsignedBigInteger('proveedor_internacional_id')->nullable()->after('proveedor_id');

            $table->foreign('proveedor_internacional_id')->on('cmp_proveedores_internacionales')->references('id')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cmp_ordenes_compras', function (Blueprint $table) {
            // Eliminar la clave foránea y la columna asociada
            $table->dropConstrainedForeignId('proveedor_internacional_id');
        });
    }
};
