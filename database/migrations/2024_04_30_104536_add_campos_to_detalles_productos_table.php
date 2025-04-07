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
//        Schema::table('detalles_productos', function (Blueprint $table) {
//            $table->text('nombre_alternativo')->nullable()->after('activo');
//            $table->boolean('es_generico')->default(false)->after('activo');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('detalles_productos', function (Blueprint $table) {
//            $table->dropColumn(['es_generico', 'nombre_alternativo']);
//        });
    }
};
