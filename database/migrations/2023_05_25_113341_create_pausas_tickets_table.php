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
        Schema::create('pausas_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('fecha_hora_pausa');
            $table->string('fecha_hora_retorno')->nullable();

            $table->unsignedBigInteger('motivo_pausa_ticket_id');
            $table->foreign('motivo_pausa_ticket_id')->references('id')->on('motivos_pausas_tickets');

            $table->unsignedBigInteger('ticket_id');
            $table->foreign('ticket_id')->references('id')->on('tickets');

            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->foreign('responsable_id')->references('id')->on('empleados')->onDelete('set null')->onUpdate('cascade');

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
        Schema::dropIfExists('pausas_tickets');
    }
};
