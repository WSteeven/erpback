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
        Schema::create('solicitud_prestamo_empresarial', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->autoIncrement(); // Nueva columna autoincremental como clave primaria
            $table->unsignedBigInteger('solicitante');
            $table->foreign('solicitante')->references('id')->on('empleados');
            $table->date('fecha');
            $table->decimal('monto', 8, 2);
            $table->decimal('plazo', 8, 2)->nullable();
            $table->unsignedBigInteger('periodo_id')->nullable();
            $table->foreign('periodo_id')->references('id')->on('periodos');
            $table->decimal('valor_utilidad', 8, 2)->nullable();
            $table->text('motivo');
            $table->text('observacion')->nullable();
            $table->text('foto');
            $table->unsignedBigInteger('estado');
            $table->foreign('estado')->references('id')->on('autorizaciones');
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
        Schema::dropIfExists('solicitud_prestamos');
    }
};
