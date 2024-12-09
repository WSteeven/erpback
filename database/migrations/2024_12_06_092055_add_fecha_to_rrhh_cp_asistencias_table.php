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
        Schema::table('rrhh_cp_asistencias', function (Blueprint $table) {
            $table->date('fecha')->after('empleado_id')->nullable(); // Añadir campo fecha después de empleado_id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rrhh_cp_asistencias', function (Blueprint $table) {
            $table->dropColumn('fecha'); 
        });
    }
};
