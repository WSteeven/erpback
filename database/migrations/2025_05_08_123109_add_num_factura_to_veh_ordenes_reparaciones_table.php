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
        Schema::table('veh_ordenes_reparaciones', function (Blueprint $table) {
            $table->string('num_factura')->after('valor_reparacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('veh_ordenes_reparaciones', function (Blueprint $table) {
           $table->dropColumn('num_factura');
        });
    }
};
