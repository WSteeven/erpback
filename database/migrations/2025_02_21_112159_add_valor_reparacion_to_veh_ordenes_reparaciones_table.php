<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Carbon;
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
            $table->decimal('valor_reparacion')->after('servicios')->nullable();
            $table->date('fecha')->after('servicios')->default(Carbon::now()->format('Y-m-d'));
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
            $table->dropColumn(['valor_reparacion', 'fecha' ]);

        });
    }
};
