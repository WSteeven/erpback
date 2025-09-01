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
        Schema::table('fr_valijas', function (Blueprint $table) {
            $table->dropForeign(['gasto_id']);
            $table->dropColumn('gasto_id');

            $table->dropForeign(['empleado_id']);
            $table->dropColumn('empleado_id');

            // Agregar nueva relación con envio_valija
            $table->unsignedBigInteger('envio_valija_id')->after('id');
            $table->foreign('envio_valija_id')->references('id')->on('fr_envios_valijas')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fr_valijas', function (Blueprint $table) {
            // Revertir cambios
            $table->dropForeign(['envio_valija_id']);
            $table->dropColumn('envio_valija_id');

            $table->unsignedBigInteger('gasto_id')->nullable();
            $table->unsignedBigInteger('empleado_id')->nullable();

            $table->foreign('gasto_id')->references('id')->on('gastos');
            $table->foreign('empleado_id')->references('id')->on('empleados');
        });
    }
};
