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
        Schema::create('intra_organigrama', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade'); // Relacion con la tabla de empleados
            $table->string('cargo');
            $table->foreignId('jefe_id')->nullable()->constrained('empleados')->onDelete('cascade'); // Jefe inmediato del empleado
            $table->string('departamento')->nullable(); // Departamento asignado manualmente
            $table->tinyInteger('nivel')->default(1); // Nivel jerárquico
            $table->enum('tipo', ['interno', 'externo'])->default('interno'); // Tipo de empleado (interno o externo)
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
        Schema::dropIfExists('intra_organigramas');
    }
};
