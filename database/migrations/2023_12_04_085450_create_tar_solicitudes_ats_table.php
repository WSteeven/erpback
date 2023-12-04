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
        Schema::create('tar_solicitudes_ats', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->foreign('ticket_id')->references('id')->on('tickets')->cascadeOnUpdate()->nullOnDelete();

            $table->unsignedBigInteger('subtarea_id')->nullable();
            $table->foreign('subtarea_id')->references('id')->on('subtareas')->cascadeOnUpdate()->nullOnDelete();

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
        Schema::dropIfExists('tar_solicitudes_ats');
    }
};
