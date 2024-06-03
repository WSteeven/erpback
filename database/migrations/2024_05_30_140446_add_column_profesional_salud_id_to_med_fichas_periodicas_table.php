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
        Schema::table('med_fichas_periodicas', function (Blueprint $table) {
            $table->unsignedBigInteger('profesional_salud_id');
            $table->foreign('profesional_salud_id')->references('id')->on('empleados')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('med_fichas_periodicas', function (Blueprint $table) {
            $table->dropColumn('profesional_salud_id');
            $table->dropForeign('profesional_salud_id');
        });
    }
};
