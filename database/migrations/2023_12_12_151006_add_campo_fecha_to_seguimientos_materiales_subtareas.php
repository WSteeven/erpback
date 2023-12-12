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
        Schema::table('seguimientos_materiales_subtareas', function (Blueprint $table) {
            $table->date('fecha')->nullable()->after('cantidad_utilizada');
            $table->foreign('tarea_id')->nullable()->after('fecha');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seguimientos_materiales_subtareas', function (Blueprint $table) {
            $table->dropIfExists('fecha');
            $table->dropIfExists('tarea_id');
        });
    }
};
