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
        Schema::table('vacaciones', function (Blueprint $table) {
            $table->unsignedBigInteger('autorizador_id')->nullable()->after('empleado_id');

            $table->foreign('autorizador_id')->references('id')->on('empleados')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vacaciones', function (Blueprint $table) {
            $table->dropForeign(['autorizador_id']); // Elimina la restricción de clave foranea
            $table->dropColumn('autorizador_id'); // Elimina la columna autorizador_id
        });
    }
};
