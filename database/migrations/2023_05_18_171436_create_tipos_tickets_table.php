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
        Schema::create('tipos_tickets', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');
            $table->boolean('activo')->default(true);

            $table->unsignedBigInteger('categoria_tipo_ticket_id')->nullable();
            $table->foreign('categoria_tipo_ticket_id')->references('id')->on('categorias_tipos_tickets')->onDelete('set null')->onUpdate('cascade');

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
        Schema::dropIfExists('tipos_tickets');
    }
};
