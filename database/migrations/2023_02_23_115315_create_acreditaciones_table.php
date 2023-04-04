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
        Schema::create('acreditaciones', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->string('id_saldo', 12);
            $table->string('descripcion_acreditacion', 1500);
            $table->decimal('monto', $precision = 19, $scale = 2);
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_tipo_saldo');
            $table->unsignedBigInteger('id_tipo_fondo');
            $table->unsignedBigInteger('id_estado');
            $table->foreign('id_estado')->references('id')->on('estado_acreditaciones');
            $table ->foreign('id_tipo_saldo')->references('id')->on('tipo_saldo');
            $table ->foreign('id_tipo_fondo')->references('id')->on('tipo_fondo');
            $table ->foreign('id_usuario')->references('id')->on('empleados');
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
        Schema::dropIfExists('acreditaciones');
    }
};
