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
        Schema::table('bod_permisos_armas', function (Blueprint $table) {
            $table->string('imagen_permiso_reverso')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bod_permisos_armas', function (Blueprint $table) {
            $table->dropColumn('imagen_permiso_reverso');
        });
    }
};
