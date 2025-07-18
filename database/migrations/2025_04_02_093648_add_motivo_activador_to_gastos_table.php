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
        Schema::table('gastos', function (Blueprint $table) {
            $table->string('motivo')->nullable();
            $table->unsignedBigInteger('activador_id')->nullable();

            $table->foreign('activador_id')->references('id')->on('empleados')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gastos', function (Blueprint $table) {
            $table->dropForeign(['activador_id']);
            $table->dropColumn(['motivo', 'activador_id']);
        });
    }
};
