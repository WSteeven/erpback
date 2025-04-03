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
        Schema::table('materiales_empleados', function (Blueprint $table) {
            $table->unique(['empleado_id','detalle_producto_id','cliente_id'], 'unique_materiales_empleados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materiales_empleados', function (Blueprint $table) {
            $table->dropUnique('unique_materiales_empleados');
        });
    }
};
