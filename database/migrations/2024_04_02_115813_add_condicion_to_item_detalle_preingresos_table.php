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
        Schema::table('item_detalle_preingreso_material', function (Blueprint $table) {
            $table->unsignedBigInteger('condicion_id')->nullable()->after('unidad_medida_id');

            $table->foreign('condicion_id')->references('id')->on('condiciones_de_productos')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_detalle_preingreso_material', function (Blueprint $table) {
            //
        });
    }
};
