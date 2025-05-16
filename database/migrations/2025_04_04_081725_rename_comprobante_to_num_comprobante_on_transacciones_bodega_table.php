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
            $table->renameColumn('comprobante', 'num_comprobante');
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
            $table->renameColumn('num_comprobante', 'comprobante');
        });
    }
};
