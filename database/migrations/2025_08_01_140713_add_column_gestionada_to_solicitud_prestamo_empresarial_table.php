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
        Schema::table('solicitud_prestamo_empresarial', function (Blueprint $table) {
            $table->boolean('gestionada')->default(false)->after('estado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solicitud_prestamo_empresarial', function (Blueprint $table) {
            $table->dropColumn('gestionada');
        });
    }
};
