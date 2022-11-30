<?php

use App\Models\Devolucion;
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
        Schema::create('devoluciones', function (Blueprint $table) {
            $table->id();
            $table->text('justificacion');
            $table->unsignedBigInteger('solicitante_id');
            $table->unsignedBigInteger('tarea_id')->nullable();
            $table->unsignedBigInteger('sucursal_id');
            $table->enum('estado', [Devolucion::CREADA, Devolucion::ANULADA])->default(Devolucion::CREADA);

            $table->timestamps();

            $table->foreign('solicitante_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tarea_id')->references('id')->on('tareas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sucursal_id')->references('id')->on('sucursales')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devoluciones');
    }
};
