<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**Importante si no funciona , ejecutar este comando:
 * composer require doctrine/dbal
 */

return new class extends Migration {
    public function up(): void
    {
        // Renombrar la columna
        Schema::table('ventas_ventas', function (Blueprint $table) {
            $table->renameColumn('orden_interna', 'fecha_ingreso');
        });

        // Cambiar el tipo a DATE
        Schema::table('ventas_ventas', function (Blueprint $table) {
            $table->date('fecha_ingreso')->change();
        });

        // Agregar 'fecha_agendamiento' después de 'fecha_ingreso'
        Schema::table('ventas_ventas', function (Blueprint $table) {
            $table->date('fecha_agendamiento')->nullable()->after('fecha_ingreso');
        });
    }

    public function down(): void
    {
        // Eliminar 'fecha_agendamiento'
        Schema::table('ventas_ventas', function (Blueprint $table) {
            $table->dropColumn('fecha_agendamiento');
        });

        // Cambiar el tipo de vuelta a string (VARCHAR)
        Schema::table('ventas_ventas', function (Blueprint $table) {
            $table->string('fecha_ingreso')->change();
        });

        // Renombrar de nuevo la columna
        Schema::table('ventas_ventas', function (Blueprint $table) {
            $table->renameColumn('fecha_ingreso', 'orden_interna');
        });
    }
};

