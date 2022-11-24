<?php

use App\Models\TipoTransaccion;
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
        Schema::create('tipos_transacciones', function (Blueprint $table) {
            $table->id();
            // $table->string('nombre');
            $table->string('nombre');//,[TipoTransaccion::INGRESO, TipoTransaccion::EGRESO]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tipos_transacciones');
    }
};
