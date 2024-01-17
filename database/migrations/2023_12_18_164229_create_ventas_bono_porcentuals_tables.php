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
        Schema::create('ventas_bonos_porcentuales', function (Blueprint $table) {
            $table->id();
            $table->integer('porcentaje');
            $table->decimal('comision',10,2);
            $table->string('tipo_vendedor');
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
        Schema::dropIfExists('ventas_bonos_porcentuales');
    }
};
