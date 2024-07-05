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
        Schema::create('veh_registros_incidentes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehiculo_id')->nullable();
            $table->unsignedBigInteger('persona_reporta_id');
            $table->unsignedBigInteger('persona_registra_id');
            $table->date('fecha');
            $table->text('descripcion');
            $table->string('tipo');
            $table->string('gravedad');
            $table->boolean('aplica_seguro');
            $table->timestamps();

            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('persona_reporta_id')->references('id')->on('empleados')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('persona_registra_id')->references('id')->on('empleados')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veh_registros_incidentes');
    }
};
