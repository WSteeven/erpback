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
            $table->string('certificado_alta')->nullable();
            $table->string('observaciones_alta')->nullable();
            $table->string('restricciones_alta')->nullable();
            $table->unsignedBigInteger('accidente_id')->nullable();

            $table->foreign('accidente_id')->references('id')->on('sso_accidentes')->nullOnDelete()->cascadeOnUpdate();
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
            $table->dropColumn(['certificado_alta','observaciones_alta','restricciones_alta','accidente_id']);
            $table->dropForeign(['accidente_id']);
        });
    }
};
