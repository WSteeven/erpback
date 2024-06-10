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
            $table->dateTime('fecha');
            $table->integer('lote')->nullable();
            $table->string('responsable_vacunacion');
            $table->string('establecimiento_salud');
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
            $table->dropColumn('fecha');
            $table->dropColumn('lote');
            $table->dropColumn('responsable_vacunacion');
            $table->dropColumn('establecimiento_salud');
        });
    }
};
