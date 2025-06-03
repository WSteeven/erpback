<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh_cp_atrasos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('justificador_id')->nullable();
            $table->unsignedBigInteger('marcacion_id');
            $table->date('fecha_atraso');
            $table->text('ocurrencia');
            $table->bigInteger('segundos_atraso');
            $table->boolean('justificado')->default(false);
            $table->text('justificacion')->nullable();
            $table->text('justificacion_atrasado')->nullable();
            $table->text('imagen_evidencia')->nullable();
            $table->boolean('revisado')->default(false);
            $table->timestamps();

            $table->unique(['empleado_id', 'marcacion_id', 'fecha_atraso', 'segundos_atraso'], 'uq_atrasos');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('justificador_id')->references('id')->on('empleados');
            $table->foreign('marcacion_id')->references('id')->on('rrhh_cp_marcaciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_cp_atrasos');
    }
};
