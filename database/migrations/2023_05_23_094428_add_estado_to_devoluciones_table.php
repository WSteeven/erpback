<?php

use App\Models\EstadoTransaccion;
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
            $table->enum('estado_bodega', [EstadoTransaccion::PENDIENTE, EstadoTransaccion::COMPLETA, EstadoTransaccion::PARCIAL, EstadoTransaccion::ANULADA])->default(EstadoTransaccion::PENDIENTE)->after('estado');
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
