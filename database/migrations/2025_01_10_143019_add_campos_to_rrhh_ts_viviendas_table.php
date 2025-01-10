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
        Schema::table('rrhh_ts_viviendas', function (Blueprint $table) {
            $table->integer('numero_plantas')->default(1);
            $table->integer('numero_personas')->default(1);
            $table->boolean('tiene_donde_evacuar')->default(false);

            $table->string('amenaza_inundacion')->nullable();
            $table->string('amenaza_deslaves')->nullable();
            $table->string('otras_amenazas_previstas')->nullable();
            $table->text('otras_amenazas')->nullable();
            $table->boolean('existe_peligro_tsunami')->default(false);
            $table->boolean('existe_peligro_lahares')->default(false);
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
