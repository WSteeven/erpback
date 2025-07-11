<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('med_antecedentes_personales', function (Blueprint $table) {
            $table->unique('ficha_preocupacional_id', 'uq_antecedente_ficha');
        });

        Schema::table('med_antecedentes_gineco_obstetricos', function (Blueprint $table) {
            $table->unique('antecedente_personal_id', 'uq_gineco_antecedente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('med_antecedentes_personales', function (Blueprint $table) {
            $table->dropUnique('uq_antecedente_ficha');
        });

        Schema::table('med_antecedentes_gineco_obstetricos', function (Blueprint $table) {
            $table->dropUnique('uq_gineco_antecedente');
        });
    }
};
