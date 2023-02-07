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
            $table->string('tipo_saldo', 250);
            $table->string('id_saldo', 12);
            $table->unsignedBigInteger('id_tipo_fondo');
            $table->string('descripcion_saldo', 1500);
            $table->decimal('saldo_anterior', $precision = 19, $scale = 2);
            $table->decimal('saldo_depositado', $precision = 19, $scale = 2);
            $table->decimal('saldo_actual', $precision = 19, $scale = 2);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_estatus');
            $table->string('transcriptor', 120);
            $table->timestamp('fecha_trans');
            $table->foreign('id_tipo_fondo')->references('id')->on('tipo_fondo');
            $table->foreign('id_estatus')->references('id')->on('estatus');
            $table->foreign('id_usuario')->references('id')->on('users');
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
