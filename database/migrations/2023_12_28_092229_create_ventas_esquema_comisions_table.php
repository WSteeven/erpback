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
        Schema::create('ventas_esquema_comisiones', function (Blueprint $table) {
            $table->id();
            $table->integer('mes_liquidacion');
            $table->string('esquema_comision');
            $table->decimal('tarifa_basica',8,2);
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
        Schema::dropIfExists('ventas_esquema_comisiones');
    }
};
