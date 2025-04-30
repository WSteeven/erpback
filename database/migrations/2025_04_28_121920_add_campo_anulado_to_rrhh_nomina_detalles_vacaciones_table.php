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
        Schema::table('rrhh_nomina_detalles_vacaciones', function (Blueprint $table) {
            $table->boolean('anulado')->default(false)->after('observacion');
            $table->text('motivo_anulacion')->nullable()->after('anulado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rrhh_nomina_detalles_vacaciones', function (Blueprint $table) {
            $table->dropColumn(['anulado', 'motivo_anulacion']);
        });
    }
};
