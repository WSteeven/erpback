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
        Schema::create('seg_actividades_bitacora', function (Blueprint $table) {
            $table->id();

            $table->timestamp('fecha_hora_inicio');
            $table->timestamp('fecha_hora_fin')->nullable();
            $table->boolean('notificacion_inmediata')->default(false);
            $table->string('actividad');
            $table->string('ubicacion')->nullable();
            $table->string('fotografia_evidencia_1')->nullable();
            $table->string('fotografia_evidencia_2')->nullable();
            $table->string('medio_notificacion')->nullable();
            $table->foreignId('tipo_evento_bitacora_id')->constrained('seg_tipos_eventos_bitacoras')->onDelete('cascade');
            $table->foreignId('bitacora_id')->constrained('seg_bitacoras')->onDelete('cascade');

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
        Schema::dropIfExists('seg_actividades_bitacora');
    }
};
