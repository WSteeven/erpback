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
        Schema::table('rrhh_cp_oficinas_biometricos', function (Blueprint $table) {
            $table->string('clave_acceso')->nullable()->after('puerto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rrhh_cp_oficinas_biometricos', function (Blueprint $table) {
            $table->dropColumn('clave_acceso');
        });
    }
};
