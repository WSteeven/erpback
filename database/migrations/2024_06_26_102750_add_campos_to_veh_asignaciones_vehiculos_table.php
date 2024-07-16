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
        Schema::table('veh_asignaciones_vehiculos', function (Blueprint $table) {
            $table->string('garaje')->nullable();
            $table->string('latitud')->nullable();
            $table->string('longitud')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('veh_asignaciones_vehiculos', function (Blueprint $table) {
            $table->dropColumn(['garaje', 'latitud', 'longitud']);
        });
    }
};
