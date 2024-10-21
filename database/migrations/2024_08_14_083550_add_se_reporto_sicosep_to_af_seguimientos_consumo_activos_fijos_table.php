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
        Schema::table('af_seguimientos_consumo_activos_fijos', function (Blueprint $table) {
            $table->boolean('se_reporto_sicosep')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('af_seguimientos_consumo_activos_fijos', function (Blueprint $table) {
            $table->dropColumn('se_reporto_sicosep');
        });
    }
};
