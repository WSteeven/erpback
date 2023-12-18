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
        Schema::table('transacciones_bodega', function (Blueprint $table) {
            $table->unsignedBigInteger('proyecto_id')->nullable()->after('motivo_id');
            $table->unsignedBigInteger('etapa_id')->nullable()->after('proyecto_id');

            $table->foreign('proyecto_id')->references('id')->on('proyectos')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('etapa_id')->references('id')->on('tar_etapas')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transacciones_bodega', function (Blueprint $table) {
            $table->dropForeign(['proyecto_id', 'etapa_id']);
            $table->dropColumn(['proyecto_id', 'etapa_id']);
        });
    }
};
