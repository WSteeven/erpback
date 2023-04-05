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
        Schema::create('transferencias_saldos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_envia_id');
            $table->unsignedBigInteger('usuario_recibe_id')->nullable();
            $table->unsignedBigInteger('estado');
            $table->decimal('monto', 10, 2);
            $table->string('motivo', 100);
            $table->string('observacion', 250)->nullable();
            $table->string('cuenta', 20);
            $table->text('comprobante', 20);
            $table->unsignedBigInteger('id_tarea')->nullable();
            $table->foreign('id_tarea')->references('id')->on('tareas');
            $table->foreign('usuario_envia_id')->references('id')->on('empleados');
            $table->foreign('usuario_recibe_id')->references('id')->on('empleados');
            $table->foreign('estado')->references('id')->on('estado_viatico');
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
        Schema::dropIfExists('transferencias');
    }
};
