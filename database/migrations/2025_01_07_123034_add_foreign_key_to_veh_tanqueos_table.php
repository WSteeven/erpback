<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('veh_tanqueos', function (Blueprint $table) {
            $table->unsignedBigInteger('bitacora_id')->nullable()->after('combustible_id');

            $table->foreign('bitacora_id')->references('id')->on('veh_bitacoras_vehiculos')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('veh_tanqueos', function (Blueprint $table) {
            $table->dropForeign('bitacora_id');
            $table->dropColumn('bitacora_id');
        });
    }
};
