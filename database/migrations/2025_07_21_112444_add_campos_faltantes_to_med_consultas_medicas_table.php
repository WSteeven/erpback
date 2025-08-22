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
        Schema::table('med_consultas_medicas', function (Blueprint $table) {
            $table->longText('restricciones_alta')->nullable();
            $table->longText('observaciones_alta')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('med_consultas_medicas', function (Blueprint $table) {
            $table->dropColumn(['restricciones_alta', 'observaciones_alta']);
        });
    }
};
