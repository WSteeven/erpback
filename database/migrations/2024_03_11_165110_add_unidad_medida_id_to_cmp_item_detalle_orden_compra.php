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
        Schema::table('cmp_item_detalle_orden_compra', function (Blueprint $table) {
            $table->unsignedBigInteger('unidad_medida_id')->after('descripcion')->nullable();

            $table->foreign('unidad_medida_id')->references('id')->on('unidades_medidas')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cmp_item_detalle_orden_compra', function (Blueprint $table) {
            $table->dropColumn('unidad_medida_id');
            $table->dropConstrainedForeignId('unidad_medida_id');
        });
    }
};
