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
        Schema::table('ventas_ventas', function (Blueprint $table) {
            // Add new columns after forma_pago
            $table->string('banco')->nullable()->after('forma_pago');
            $table->string('numero_tarjeta')->nullable()->after('banco');
            $table->string('tipo_cuenta')->nullable()->after('numero_tarjeta');

            // Modify orden_interna to be nullable
            $table->string('orden_interna')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventas_ventas', function (Blueprint $table) {
            // Drop the new columns
            $table->dropColumn(['banco', 'numero_tarjeta', 'tipo_cuenta']);

            // Revert orden_interna to not nullable
            $table->string('orden_interna')->nullable(false)->change();
        });
    }
};
