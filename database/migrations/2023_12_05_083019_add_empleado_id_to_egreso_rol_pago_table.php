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
        Schema::table('egreso_rol_pago', function (Blueprint $table) {
            $table->unsignedBigInteger('empleado_id')->nullable()->after('monto');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('egreso_rol_pago', function (Blueprint $table) {

        });
    }
};
