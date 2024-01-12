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
        Schema::table('tar_subcentros_costos', function (Blueprint $table) {
            $table->unsignedBigInteger('grupo_id')->nullable()->after('centro_costo_id');

            $table->foreign('grupo_id')->on('grupos')->references('id')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tar_subcentros_costos', function (Blueprint $table) {
            $table->dropForeign('grupo_id');
            $table->dropColumn('grupo_id');
        });
    }
};
