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
        Schema::table('subtareas', function (Blueprint $table) {
            $table->unsignedBigInteger('causa_intervencion_id')->nullable();
            $table->foreign('causa_intervencion_id')->references('id')->on('causas_intervenciones')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tipos_trabajos', function (Blueprint $table) {
            $table->dropColumn('causa_intervencion_id');
        });
    }
};
