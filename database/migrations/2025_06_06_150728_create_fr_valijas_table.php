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
        Schema::create('fr_valijas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gasto_id')->nullable();
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('departamento_id')->nullable();
            $table->text('descripcion');
            $table->unsignedBigInteger('destinatario_id')->nullable();
            $table->text('imagen_evidencia');
            $table->timestamps();

            $table->foreign('gasto_id')->references('id')->on('gastos');
            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('departamento_id')->references('id')->on('departamentos');
            $table->foreign('destinatario_id')->references('id')->on('empleados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_valijas');
    }
};
