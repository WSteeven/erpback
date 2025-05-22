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
        Schema::table('ventas_ventas', function (Blueprint $table) {
            $table->unsignedBigInteger('estado_id')->nullable();
            $table->text('adicionales')->nullable();
            $table->foreign('estado_id')->references('id')->on('ventas_estados_claro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas_ventas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('estado_id');
//            $table->dropForeign('estado_id');
            $table->dropColumn('adicionales');
        });
    }
};
