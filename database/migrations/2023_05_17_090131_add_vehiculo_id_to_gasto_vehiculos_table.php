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
            $table->unsignedBigInteger('id_vehiculo')->after('kilometraje')->nullable();
            $table->foreign('id_vehiculo')->references('id')->on('vehiculos')->onDelete('cascade');
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
            $table->dropColumn('id_vehiculo');
        });
    }
};
