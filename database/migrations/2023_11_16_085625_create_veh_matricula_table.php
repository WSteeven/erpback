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
        Schema::create('veh_matriculas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehiculo_id')->nullable();
            $table->date('fecha_matricula')->nullable();
            $table->date('proxima_matricula')->nullable();
            $table->string('matriculador')->nullable();
            $table->boolean('matriculado')->default(false);
            $table->string('observacion')->nullable();
            $table->double('monto')->nullable();
            $table->timestamps();

            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veh_matriculas');
    }
};
