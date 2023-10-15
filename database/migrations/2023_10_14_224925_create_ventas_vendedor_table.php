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
        Schema::create('ventas_vendedor', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_vendedor');
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('modalidad_id');
            $table->timestamps();
            $table->foreign('empleado_id', 'fk_empleado_vendedor_id')->references('id')->on('empleados')->cascadeOnUpdate();
            $table->foreign('modalidad_id', 'fk_modalidad_id')->references('id')->on('ventas_modalidad')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_vendedor');
    }
};
