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
        Schema::table('prestamo_empresarial', function (Blueprint $table) {
            $table->text('motivo')->after('estado')->nullable();
          //  $table->enum('estado', ['ACTIVO', 'FINALIZADO', 'INACTIVO'])->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prestamo_empresarial', function (Blueprint $table) {
            //
        });
    }
};
