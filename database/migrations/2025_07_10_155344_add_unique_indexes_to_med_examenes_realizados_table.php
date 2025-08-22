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
        Schema::table('med_examenes_realizados', function (Blueprint $table) {
            $table->unique(['examen_id', 'ficha_preocupacional_id'], 'uq_examen_ficha');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('med_examenes_realizados', function (Blueprint $table) {
            $table->dropUnique('uq_examen_ficha');
        });
    }
};
