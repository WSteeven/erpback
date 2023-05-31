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
        Schema::create('archivos_tickets', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->string('ruta');
            $table->integer('tamanio_bytes');

            // Foreign keys
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
        Schema::dropIfExists('archivos_tickets');
    }
};
