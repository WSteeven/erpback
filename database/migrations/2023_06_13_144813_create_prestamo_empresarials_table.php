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
        Schema::create('prestamo_empresarials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitante');
            $table->foreign('solicitante')->references('id')->on('empleados');
            $table->date('fecha');
            $table->decimal('valor',8,2);
            $table->integer('utilidad',4);
            $table->decimal('valor_utilidad',8,2);
            $table->unsignedBigInteger('id_forma_pago');
            $table->decimal('plazo',8,2);
            $table->enum('estado',['ACTIVO','FINALIZADO']);
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
        Schema::dropIfExists('prestamo_empresarials');
    }
};
