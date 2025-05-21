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
            $table->decimal('iva', 5, 2)->default(15.00)->after('logo_marca_agua')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configuraciones_generales', function (Blueprint $table) {
            $table->decimal('iva', 5, 2)->default(15.00)->after('id')->change();
        });
    }
};
