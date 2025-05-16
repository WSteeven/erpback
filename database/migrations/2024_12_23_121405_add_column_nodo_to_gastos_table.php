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
            $table->unsignedBigInteger('nodo_id')->nullable()->after('observacion');

            $table->foreign('nodo_id')->references('id')->on('tar_nodos');
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
            $table->dropForeign('nodo_id');
            $table->dropColumn('nodo_id');
        });
    }
};
