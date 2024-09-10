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
        Schema::table('veh_bitacoras_vehiculos', function (Blueprint $table) {
            $table->unsignedBigInteger('registrador_id')->nullable()->after('vehiculo_id');

            $table->foreign('registrador_id')->references('id')->on('empleados')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('veh_bitacoras_vehiculos', function (Blueprint $table) {
            // primero borramos la clave foranea
            $table->dropForeign('registrador_id');
            // luego borramos la columna
            $table->dropColumn('registrador_id');
        });
    }
};
