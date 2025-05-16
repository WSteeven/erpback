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
        Schema::table('fr_valor_acreditar', function (Blueprint $table) {
            $table->double('saldo_empleado')->after('monto_modificado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fr_valor_acreditar', function (Blueprint $table) {
            $table->dropColumn('saldo_empleado');
        });
    }
};
