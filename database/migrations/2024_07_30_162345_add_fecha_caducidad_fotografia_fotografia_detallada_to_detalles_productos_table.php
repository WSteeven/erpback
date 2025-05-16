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
            $table->date('fecha_caducidad')->nullable();
            $table->string('fotografia')->nullable();
            $table->string('fotografia_detallada')->nullable();
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
            $table->dropColumn('fecha_caducidad');
            $table->dropColumn('fotografia');
            $table->dropColumn('fotografia_detallada');
        });
    }
};
