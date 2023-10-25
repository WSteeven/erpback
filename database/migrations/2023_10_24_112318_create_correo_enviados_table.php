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
        Schema::create('correos_enviados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_envia_id')->nullable();
            $table->text('remitente');
            $table->string('correo_destinatario');
            $table->timestamp('fecha_hora');
            $table->text('asunto');
            $table->unsignedBigInteger('notificable_id');
            $table->string('notificable_type');
            $table->timestamps();

            $table->foreign('empleado_envia_id')->references('id')->on('empleados')->cascadeOnUpdate()->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('correos_enviados');
    }
};
