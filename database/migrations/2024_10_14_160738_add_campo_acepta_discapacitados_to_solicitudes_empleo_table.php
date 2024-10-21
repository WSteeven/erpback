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
        Schema::table('rrhh_contratacion_solicitudes_nuevas_vacantes', function (Blueprint $table) {
            $table->boolean('acepta_discapacitados')->default(false);
            $table->string('edad_personalizada')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rrhh_contratacion_solicitudes_nuevas_vacantes', function (Blueprint $table) {
            $table->dropColumn(['acepta_discapacitados', 'edad_personalizada']);
        });
    }
};
