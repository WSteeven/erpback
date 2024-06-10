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
        Schema::table('provincias', function (Blueprint $table) {
            // if (!Schema::hasColumn('provincias', 'pais_id')) {
            $table->unsignedBigInteger('pais_id')->nullable()->after('cod_provincia');
            $table->foreign('pais_id')->references('id')->on('paises')->cascadeOnDelete()->cascadeOnUpdate();
            // }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provincias', function (Blueprint $table) {
            //
        });
    }
};
