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
        Schema::table('gasto_vehiculos', function (Blueprint $table) {
            $table->boolean('es_vehiculo_alquilado')->after('id_vehiculo')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gasto_vehiculos', function (Blueprint $table) {
            $table->dropColumn('es_vehiculo_alquilado');
        });
    }
};
