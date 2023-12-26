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
        Schema::table('ventas_vendedor', function (Blueprint $table) {
            $table->unsignedBigInteger('jefe_inmediato_id')->after('tipo_vendedor')->nullable();
            $table->foreign('jefe_inmediato_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ventas_vendedor', function (Blueprint $table) {


        });
    }
};
