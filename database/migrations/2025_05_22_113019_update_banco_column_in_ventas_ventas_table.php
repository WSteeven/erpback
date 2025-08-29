<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateBancoColumnInVentasVentasTable extends Migration
{
    public function up()
    {
        // Limpiar valores que no correspondan a bancos válidos
        /*DB::statement('
            UPDATE ventas_ventas
            SET banco = NULL
            WHERE banco IS NOT NULL
              AND banco NOT IN (SELECT id FROM bancos)
        ');*/

        // Cambiar el tipo y agregar la foreign key
/*        Schema::table('ventas_ventas', function (Blueprint $table) {
            $table->unsignedBigInteger('banco')->nullable()->change();

            $table->foreign('banco')
                ->references('id')
                ->on('bancos')
                ->onDelete('cascade');
        });*/
    }

    public function down()
    {
/*        Schema::table('ventas_ventas', function (Blueprint $table) {
            $table->dropForeign(['banco']);
            $table->string('banco')->change(); // O vuelve al tipo anterior
        });*/
    }
}

