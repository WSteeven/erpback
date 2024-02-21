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
        Schema::table('detalle_devolucion_producto', function (Blueprint $table) {
            $table->text('observacion')->nullable()->after('devuelto');
            $table->unsignedBigInteger('condicion_id')->nullable()->after('devuelto');

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
        Schema::table('detalle_devolucion_producto', function (Blueprint $table) {
            $table->dropForeign('condicion_id');
            $table->dropColumn(['observacion', 'condicion_id']);
        });
    }
};
