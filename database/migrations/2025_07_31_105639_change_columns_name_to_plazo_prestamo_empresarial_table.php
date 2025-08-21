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
        Schema::table('plazo_prestamo_empresarial', function (Blueprint $table) {
            $table->renameColumn('valor_couta', 'valor_cuota');
            $table->renameColumn('pago_couta', 'pago_cuota');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plazo_prestamo_empresarial', function (Blueprint $table) {
            $table->renameColumn('valor_cuota', 'valor_couta');
            $table->renameColumn('pago_cuota', 'pago_couta');
        });
    }
};
