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
        Schema::table('rrhh_ts_situaciones_socioeconomicas', function (Blueprint $table) {
            $table->string('especificacion_terreno')->nullable();
            $table->string('especificacion_bienes')->nullable();
            $table->string('especificacion_ingresos_adicionales')->nullable();
            $table->decimal('valor_apoyo_familiar_externo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rrhh_ts_situaciones_socioeconomicas', function (Blueprint $table) {
            $table->dropColumn([
                'especificacion_terreno',
                'especificacion_bienes',
                'especificacion_ingresos_adicionales',
                'valor_apoyo_familiar_externo'
            ]);
        });
    }
};
