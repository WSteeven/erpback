<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vacaciones', function (Blueprint $table) {
            $table->date('fecha_inicio_rango1_vacaciones')->nullable();
            $table->date('fecha_fin_rango1_vacaciones')->nullable();
            $table->date('fecha_inicio_rango2_vacaciones')->nullable();
            $table->date('fecha_fin_rango2_vacaciones')->after('fecha_inicio_rango2_vacaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vacaciones', function (Blueprint $table) {
            $table->dropColumn(['fecha_inicio_rango1_vacaciones', 'fecha_fin_rango1_vacaciones', 'fecha_inicio_rango2_vacaciones', 'fecha_fin_rango2_vacaciones']);
        });
    }
};
