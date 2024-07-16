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
        Schema::table('med_esquemas_vacunas', function (Blueprint $table) {
            $table->boolean('es_dosis_unica');
            $table->dateTime('fecha_caducidad')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('med_esquemas_vacunas', function (Blueprint $table) {
            $table->dropColumn('es_dosis_unica');
            $table->dropColumn('fecha_caducidad');
        });
    }
};
