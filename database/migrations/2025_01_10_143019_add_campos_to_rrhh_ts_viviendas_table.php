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
        Schema::table('rrhh_ts_viviendas', function (Blueprint $table) {
            $table->integer('numero_plantas')->default(1);
            $table->integer('numero_personas')->default(1);
            $table->boolean('tiene_donde_evacuar')->default(false);

            $table->string('')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rrhh_ts_viviendas', function (Blueprint $table) {
            //
        });
    }
};
