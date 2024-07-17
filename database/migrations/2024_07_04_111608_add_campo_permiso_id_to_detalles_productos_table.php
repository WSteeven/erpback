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
        Schema::table('detalles_productos', function (Blueprint $table) {
            $table->unsignedBigInteger('permiso_id')->nullable();

            $table->foreign('permiso_id')->references('id')->on('bod_permisos_armas')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detalles_productos', function (Blueprint $table) {
            //
        });
    }
};
