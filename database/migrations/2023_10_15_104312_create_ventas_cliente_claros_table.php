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
        Schema::create('ventas_clientes_claro', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->string('identificacion');
            $table->string('nombres');
            $table->string('apellidos');
            $table->text('direccion');
            $table->string('telefono1');
            $table->string('telefono2')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->foreign('supervisor_id')->references('id')->on('empleados')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_clientes_claro');
    }
};
