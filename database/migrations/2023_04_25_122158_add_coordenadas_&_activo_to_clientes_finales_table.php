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
        Schema::table('clientes_finales', function (Blueprint $table) {
            $table->dropColumn('coordenada_latitud');
            $table->dropColumn('coordenada_longitud');

            $table->string('cedula', 10)->unique()->nullable()->after('referencia');
            $table->string('correo', 100)->unique()->nullable()->after('cedula');
            $table->string('coordenadas')->nullable()->after('correo');
            $table->boolean('activo')->default(true)->after('coordenadas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes_finales', function (Blueprint $table) {
            $table->string('coordenada_latitud')->nullable()->after('referencia');
            $table->string('coordenada_longitud')->nullable()->after('coordenadas');

            $table->dropColumn('cedula');
            $table->dropColumn('correo');
            $table->dropColumn('coordenadas');
            $table->dropColumn('activo');
        });
    }
};
