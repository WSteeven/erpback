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
        Schema::table('rrhh_ts_fichas_socioeconomicas', function (Blueprint $table) {
            $table->string('contacto_emergencia_externo')->nullable();
            $table->string('parentesco_contacto_emergencia_externo')->nullable();
            $table->string('telefono_contacto_emergencia_externo')->nullable();
            $table->unsignedBigInteger('ciudad_contacto_emergencia_externo_id')->nullable();


            $table->foreign('ciudad_contacto_emergencia_externo_id', 'fk_ciudad_contacto_externo')->references('id')->on('cantones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rrhh_ts_fichas_socioeconomicas', function (Blueprint $table) {
            $table->dropForeign('ciudad_contacto_emergencia_externo_id');
            $table->dropColumn([
                'contacto_emergencia_externo',
                'parentesco_contacto_emergencia_externo',
                'telefono_contacto_emergencia_externo',
                'ciudad_contacto_emergencia_externo_id',
            ]);
        });
    }
};
