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
        Schema::table('actividades_realizadas_seguimientos_tickets', function (Blueprint $table) {
            $table->unsignedBigInteger('responsable_id')->after('ticket_id');
            $table->foreign('responsable_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade')->name('act_rea_seg_tic_responsable_id_foreign'); ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('actividades_realizadas_seguimientos_tickets', function (Blueprint $table) {
            $table->dropForeign(['responsable_id']);
            $table->dropColumn('responsable_id');
        });
    }
};
