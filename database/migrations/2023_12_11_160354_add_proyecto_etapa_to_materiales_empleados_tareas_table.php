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
        Schema::table('materiales_empleados_tareas', function (Blueprint $table) {
            $table->unsignedBigInteger('proyecto_id')->nullable();
            $table->unsignedBigInteger('etapa_id')->nullable();

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
        Schema::table('materiales_empleados_tareas', function (Blueprint $table) {
            $table->dropForeign('proyecto_id');
            $table->dropForeign('etapa_id');
        });
    }
};
