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
        Schema::table('empleados', function (Blueprint $table) {

            // $table->dropColumn('fecha_ingreso');
            // $table->renameColumn('fecha_ingreso2', 'fecha_ingreso');
            // $table->dropColumn('fecha_vinculacion');
            // $table->renameColumn('fecha_vinculacion2', 'fecha_vinculacion');
            // $table->dropColumn('fecha_salida');
            // $table->renameColumn('fecha_salida2', 'fecha_salida');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empleados', function (Blueprint $table) {
            //
        });
    }
};
