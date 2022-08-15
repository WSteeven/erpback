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
            $table->string('identificacion');
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('telefono')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            /* $table->double('saldo_inicial')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable(); */

            $table->unsignedBigInteger('jefe_id')->nullable();
            $table->foreign('jefe_id')->references('id')->on('empleados')->onUpdate('cascade');

            $table->unsignedBigInteger('usuario_id'); //fk usuario que inicia sesion
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('localidad_id');
            $table->foreign('localidad_id')->references('id')->on('localidades');

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
        Schema::dropIfExists('empleados');
    }
};
