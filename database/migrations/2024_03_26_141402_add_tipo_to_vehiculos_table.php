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
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_vehiculo_id')->after('num_motor')->nullable();
            $table->boolean('tiene_gravamen')->after('aire_acondicionado')->default(false);
            $table->text('prendador')->nullable(); //entidad que ofrece el credito en garantía

            $table->foreign('tipo_vehiculo_id')->references('id')->on('veh_tipos_vehiculos')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            $table->dropForeign('tipo_vehiculo_id');
            $table->dropColumn(['tipo_vehiculo_id', 'tiene_gravamen', 'prendador']);
        });
    }
};
