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
        Schema::create('control_cambios', function (Blueprint $table) {
            $table->id();
            $table->string('numero_elemento');
            $table->text('cambios');
            $table->string('georeferencia_x')->nullable();
            $table->string('georeferencia_y')->nullable();
            $table->string('aprobado_por')->nullable();

            // Foreign keys
            $table->unsignedBigInteger('subtarea_id');
            $table->foreign('subtarea_id')->references('id')->on('subtareas')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('control_cambios');
    }
};
