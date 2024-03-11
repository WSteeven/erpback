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
        Schema::table('preingresos_materiales', function (Blueprint $table) {
            $table->unsignedBigInteger('solicitante_id')->nullable()->after('autorizador_id');
            $table->unsignedBigInteger('proyecto_id')->nullable()->after('fecha');
            $table->unsignedBigInteger('etapa_id')->nullable()->after('proyecto_id');

            $table->foreign('solicitante_id')->references('id')->on('empleados')->cascadeOnUpdate()->nullOnDelete();
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
        Schema::table('preingresos_materiales', function (Blueprint $table) {
            $table->dropForeign('solicitante_id');
            $table->dropForeign('proyecto_id');
            $table->dropForeign('etapa_id');
        });
    }
};
