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
        Schema::table('tar_transf_produc_emplea', function (Blueprint $table) {
            $table->string('novedades_transferencia_recibida')->nullable()->after('observacion_aut');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tar_transf_produc_emplea', function (Blueprint $table) {
            $table->dropColumn(['novedades_transferencia_recibida']);
        });
    }
};
