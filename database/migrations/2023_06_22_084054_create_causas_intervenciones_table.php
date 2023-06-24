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
        Schema::create('causas_intervenciones', function (Blueprint $table) {
            $table->id();

            $table->string('nombre');

            // Foreign keys
            $table->unsignedBigInteger('tipo_trabajo_id');
            $table->foreign('tipo_trabajo_id')->references('id')->on('tipos_trabajos')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('causas_intervenciones');
    }
};
