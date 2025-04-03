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
        Schema::table('rrhh_ts_hijos', function (Blueprint $table) {
            $table->string('tipo')->nullable();
            $table->string('genero')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rrhh_ts_hijos', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'genero']);
        });
    }
};
