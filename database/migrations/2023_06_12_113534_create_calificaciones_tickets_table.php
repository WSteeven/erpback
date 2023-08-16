<?php

use App\Models\CalificacionTicket;
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
        Schema::create('calificaciones_tickets', function (Blueprint $table) {
            $table->id();

            $table->enum('solicitante_o_responsable', [CalificacionTicket::SOLICITANTE, CalificacionTicket::RESPONSABLE]);
            $table->text('observacion');
            $table->integer('calificacion');

            // Foreign keys
            $table->unsignedBigInteger('calificador_id');
            $table->foreign('calificador_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('ticket_id');
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('calificaciones_tickets');
    }
};
