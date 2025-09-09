<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cap_capacitacion_empleado', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plantilla_capacitacion_id');
            $table->unsignedBigInteger('empleado_id');
            $table->timestamps();

            //  Foreign keys
            $table->foreign('plantilla_capacitacion_id')
                  ->references('id')
                  ->on('cap_plantilla_capacitaciones')
                  ->onDelete('cascade');

            $table->foreign('empleado_id')
                  ->references('id')
                  ->on('empleados');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cap_capacitacion_empleado');
    }
};
