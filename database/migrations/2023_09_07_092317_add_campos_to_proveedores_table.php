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
        Schema::table('proveedores', function (Blueprint $table) {
            $table->text('causa_inactivacion')->nullable()->after('estado'); //cuando se inactive el proveedor poner el motivo
            $table->text('referencia')->nullable();
            $table->string('forma_pago')->nullable();
            $table->string('plazo_credito')->nullable();
            $table->string('anticipos')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            //
        });
    }
};
