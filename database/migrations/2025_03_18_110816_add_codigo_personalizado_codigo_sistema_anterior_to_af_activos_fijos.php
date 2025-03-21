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
        Schema::table('af_activos_fijos', function (Blueprint $table) {
            $table->string('codigo_personalizado')->nullable()->after('id');
            $table->string('codigo_sistema_anterior')->nullable()->after('codigo_personalizado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('af_activos_fijos', function (Blueprint $table) {
            $table->dropColumn('codigo_personalizado');
            $table->dropColumn('codigo_sistema_anterior');
        });
    }
};
