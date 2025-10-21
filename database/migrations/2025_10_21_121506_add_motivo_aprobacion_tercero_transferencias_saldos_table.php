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
        Schema::table('transferencias_saldos', function (Blueprint $table) {
            $table->longText('motivo_aprobacion_tercero')->nullable();
            $table->unsignedBigInteger('usuario_tercero_aprueba_id')->nullable();

            $table->foreign('usuario_tercero_aprueba_id')->references('id')->on('empleados')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transferencias_saldos', function (Blueprint $table) {
            $table->dropForeign(['usuario_tercero_aprueba_id']);
            $table->dropColumn('motivo_aprobacion_tercero');
            $table->dropColumn('usuario_tercero_aprueba_id');
        });
    }
};
