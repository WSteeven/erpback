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
        Schema::table('seg_bitacoras', function (Blueprint $table) {
            $table->boolean('revisado_por_supervisor')->default(false)->after('observaciones');
            $table->text('retroalimentacion_supervisor')->nullable()->after('revisado_por_supervisor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seg_bitacoras', function (Blueprint $table) {
            $table->dropColumn(['revisado_por_supervisor', 'retroalimentacion_supervisor']);
        });
    }
};
