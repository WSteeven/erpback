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
        Schema::create('saldo_grupo', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->decimal('saldo_anterior', $precision = 19, $scale = 2);
            $table->decimal('saldo_depositado', $precision = 19, $scale = 2);
            $table->decimal('saldo_actual', $precision = 19, $scale = 2);
            $table->string('tipo_saldo', 100);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('empleados');
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
        Schema::dropIfExists('saldo_grupo');
    }
};
