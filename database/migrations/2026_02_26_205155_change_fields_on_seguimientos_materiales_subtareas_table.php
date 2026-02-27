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
            $table->string('fecha')->nullable()->change();
            $table->unsignedBigInteger('tarea_id')->nullable()->change();
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
            $table->string('fecha')->change();
            $table->unsignedBigInteger('tarea_id')->change();
        });
    }
};
