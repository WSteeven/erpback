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
            //No se hace nada con la columna garaje, solo no se utilizará más 
            $table->unsignedBigInteger('garaje_id')->nullable();

            $table->foreign('garaje_id')->references('id')->on('veh_garajes')->nullOnDelete()->cascadeOnUpdate();
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
            $table->dropConstrainedForeignId('garaje_id');
        });
    }
};
