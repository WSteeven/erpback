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
        Schema::create('fr_valor_acreditar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('acreditacion_semana_id');
            $table->decimal('monto_generado',8,4);
            $table->decimal('monto_modificado',8,4);
            $table->timestamps();
            $table->foreign('empleado_id')->references('id')->on('empleados')->cascadeOnUpdate();
            $table->foreign('acreditacion_semana_id')->references('id')->on('fr_acreditacion_semana')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_valor_acreditar');
    }
};
