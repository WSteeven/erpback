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
        Schema::table('med_citas_medicas', function (Blueprint $table) {
            $table->string('tipo_cambio_cargo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('med_citas_medicas', function (Blueprint $table) {
            $table->dropColumn('tipo_cambio_cargo');
        });
    }
};
