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
        Schema::table('tar_det_tran_prod_emp', function (Blueprint $table) {
            $table->integer('recibido')->nullable()->after('cantidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tar_det_tran_prod_emp', function (Blueprint $table) {
            $table->dropColumn('recibido');
        });
    }
};
