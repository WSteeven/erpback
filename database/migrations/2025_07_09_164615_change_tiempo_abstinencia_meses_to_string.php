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
        Schema::table('med_resultados_habitos_toxicos', function (Blueprint $table) {
            $table->string('tiempo_abstinencia_meses')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('med_resultados_habitos_toxicos', function (Blueprint $table) {
            $table->integer('tiempo_abstinencia_meses')->nullable()->change();
        });
    }
};
