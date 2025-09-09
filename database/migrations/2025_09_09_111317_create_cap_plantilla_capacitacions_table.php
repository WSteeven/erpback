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
    public function up(): void
    {
        Schema::create('cap_plantilla_capacitaciones', function (Blueprint $table) {
            $table->id();
            $table->string('tema');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('modalidad', ['Interno', 'Externo']);
            $table->unsignedBigInteger('capacitador_id'); // empleado que da la capacitación
            $table->timestamps();

            $table->foreign('capacitador_id')->references('id')->on('empleados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('cap_plantilla_capacitaciones');
    }
};
