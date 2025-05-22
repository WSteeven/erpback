<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Cambia el tipo de la columna 'orden_id' en la tabla 'ventas_ventas'
 * de bigint sin signo a string (VARCHAR)
 *
 * El método ->change() requiere que tengas doctrine/dbal instalado:
 * composer require doctrine/dbal
 *
 */

class ChangeOrdenIdTypeInVentasVentasTable extends Migration
{
    public function up()
    {
        Schema::table('ventas_ventas', function (Blueprint $table) {
            // Cambiar el tipo a string (VARCHAR)
            $table->string('orden_id', 20)->change();
        });
    }

    public function down()
    {
        Schema::table('ventas_ventas', function (Blueprint $table) {
            // Revertir al tipo anterior si era bigint sin signo
            $table->unsignedBigInteger('orden_id')->change();
        });
    }
}
