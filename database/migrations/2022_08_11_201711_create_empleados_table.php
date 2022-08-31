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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('identificacion')->unique()->nullable();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('telefono')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->unsignedBigInteger('jefe_id')->nullable();
            $table->unsignedBigInteger('usuario_id'); //fk usuario que inicia sesion
            $table->unsignedBigInteger('sucursal_id');
            $table->unsignedBigInteger('grupo_id')->nullable();
            $table->boolean('estado')->default(true);
            $table->timestamps();


            /* $table->double('saldo_inicial')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable(); */


            $table->foreign('jefe_id')->references('id')->on('empleados')->onDelete(null)->onUpdate('cascade');
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sucursal_id')->references('id')->on('sucursales');
            $table->foreign('grupo_id')->references('id')->on('grupos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleados');
    }
};
