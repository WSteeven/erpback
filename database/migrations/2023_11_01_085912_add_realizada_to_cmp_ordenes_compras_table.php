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
        Schema::table('cmp_ordenes_compras', function (Blueprint $table) {
            $table->boolean('revisada_compras')->default(false)->after('fecha');
            $table->text('observacion_compras')->nullable()->after('revisada_compras');
            $table->boolean('realizada')->default(false)->after('observacion_compras');
            $table->text('observacion_realizada')->nullable()->after('realizada');
            $table->boolean('pagada')->default(false)->after('realizada');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cmp_ordenes_compras', function (Blueprint $table) {
            //
        });
    }
};
