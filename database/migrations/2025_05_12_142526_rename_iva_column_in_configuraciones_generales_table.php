<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('configuraciones_generales', function (Blueprint $table) {
            // Renombrar la columna
            $table->renameColumn('IVA', 'iva');
        });

        Schema::table('configuraciones_generales', function (Blueprint $table) {
            // Cambiar la posición de la columna usando "after"
            $table->decimal('iva', 5, 2)->default(15.00)->after('logo_marca_agua')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configuraciones_generales', function (Blueprint $table) {
            // Volver a su nombre original
            $table->renameColumn('iva', 'IVA');
        });

        Schema::table('configuraciones_generales', function (Blueprint $table) {
            // Volver a la posición original
            $table->decimal('IVA', 5, 2)->default(15.00)->after('id')->change();
        });
    }
};
