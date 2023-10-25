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
        Schema::table('cmp_ordenes_compras', function (Blueprint $table) {
            $table->unsignedBigInteger('tarea_id')->nullable()->after('iva');
            $table->text('file')->nullable()->after('tarea_id');

            $table->foreign('tarea_id')->references('id')->on('tareas')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cmp_ordenes_compras', function (Blueprint $table) {
            //
        });
    }
};
