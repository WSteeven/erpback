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
        Schema::create('fr_saldos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->double('saldo_anterior');
            $table->double('saldo_depositado');
            $table->double('saldo_actual');
            $table->string('tipo_saldo');
            $table->bigInteger('saldoable_id');
            $table->string('saldoable_type');
            /**Llaves Foraneas */
            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->on('empleados')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
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
        Schema::dropIfExists('fr_saldos');
    }
};
