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
        Schema::table('devoluciones', function (Blueprint $table) {
            $table->text('observacion_aut')->nullable()->after('solicitante_id');
            $table->unsignedBigInteger('autorizacion_id')->nullable()->after('solicitante_id');
            $table->unsignedBigInteger('per_autoriza_id')->nullable()->after('solicitante_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devoluciones', function (Blueprint $table) {
            //
        });
    }
};
