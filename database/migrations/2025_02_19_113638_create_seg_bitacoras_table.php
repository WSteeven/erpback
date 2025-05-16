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
        Schema::create('seg_bitacoras', function (Blueprint $table) {
            $table->id();

            $table->timestamp('fecha_hora_inicio_turno');
            $table->timestamp('fecha_hora_fin_turno')->nullable();
            $table->string('jornada');
            $table->string('observaciones')->nullable();
            $table->json('prendas_recibidas_ids')->nullable();
            $table->foreignId('zona_id')->constrained('seg_zonas')->onDelete('cascade');
            $table->foreignId('agente_turno_id')->constrained('empleados')->onDelete('cascade');
            $table->foreignId('protector_id')->constrained('empleados')->onDelete('cascade');
            $table->foreignId('conductor_id')->constrained('empleados')->onDelete('cascade');

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
        Schema::dropIfExists('seg_bitacoras');
    }
};
