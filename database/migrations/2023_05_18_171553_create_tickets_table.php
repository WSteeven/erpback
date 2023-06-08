<?php

use App\Models\Ticket;
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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->string('codigo')->unique();
            $table->string('asunto');
            $table->text('descripcion');
            $table->enum('prioridad', [Ticket::ALTA, Ticket::MEDIA, Ticket::BAJA, Ticket::EMERGENCIA]);
            $table->timestamp('fecha_hora_limite')->nullable();
            $table->enum('estado', [Ticket::RECHAZADO, Ticket::ASIGNADO, Ticket::REASIGNADO, Ticket::EJECUTANDO, Ticket::PAUSADO, Ticket::CANCELADO, Ticket::FINALIZADO_SIN_SOLUCION, Ticket::FINALIZADO_SOLUCIONADO, Ticket::CALIFICADO]);
            $table->text('observaciones_solicitante')->nullable();
            $table->integer('calificacion_solicitante')->nullable();

            $table->timestamp('fecha_hora_asignacion')->nullable();
            $table->timestamp('fecha_hora_ejecucion')->nullable();
            $table->timestamp('fecha_hora_finalizado')->nullable();
            $table->timestamp('fecha_hora_cancelado')->nullable();
            $table->timestamp('fecha_hora_calificado')->nullable();

            // Foreign keys
            $table->unsignedBigInteger('solicitante_id');
            $table->foreign('solicitante_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->foreign('responsable_id')->references('id')->on('empleados')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedBigInteger('departamento_responsable_id')->nullable();
            $table->foreign('departamento_responsable_id')->references('id')->on('departamentos')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedBigInteger('tipo_ticket_id');
            $table->foreign('tipo_ticket_id')->references('id')->on('tipos_tickets')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('motivo_cancelado_ticket_id')->nullable();
            $table->foreign('motivo_cancelado_ticket_id')->references('id')->on('motivos_cancelados_tickets')->onDelete('set null')->onUpdate('cascade');

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
        Schema::dropIfExists('tickets');
    }
};
