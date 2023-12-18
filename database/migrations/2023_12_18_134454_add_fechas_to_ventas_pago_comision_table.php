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
        Schema::table('ventas_pago_comision', function (Blueprint $table) {
           $table->date('fecha_inicio')->after('valor')->nullable();
           $table->date('fecha_fin')->after('fecha_inicio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas_pago_comision', function (Blueprint $table) {
            $table->dropColumn('fecha_inicio');
            $table->dropColumn('fecha_fin');
        });
    }
};
