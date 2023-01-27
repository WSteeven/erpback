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
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();

            $table->string('codigo_proyecto')->unique();
            $table->string('nombre')->unique();
            $table->string('nodo_interconexion');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('finalizado')->default(false);
            // $table->double('costo');

            // Foreign key
            $table->unsignedBigInteger('coordinador_id');
            $table->foreign('coordinador_id')->references('id')->on('empleados')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('canton_id');
            $table->foreign('canton_id')->references('id')->on('cantones')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('proyectos');
    }
};
