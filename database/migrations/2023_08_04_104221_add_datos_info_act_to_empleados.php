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
            // $table->string('correo_personal')->after('telefono_empresa');
            $table->string('nivel_academico')->after('fecha_ingreso');
            $table ->string('talla_pantalon')->after('talla_guantes');
            $table->unsignedBigInteger('banco')->after('talla_pantalon')->nullable();
            $table->foreign('banco')->references('id')->on('bancos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
