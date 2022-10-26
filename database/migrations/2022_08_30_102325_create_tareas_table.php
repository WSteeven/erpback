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
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_tarea');
            $table->string('codigo_tarea_cliente')->nullable();
            $table->string('fecha_solicitud')->nullable();
            $table->string('hora_solicitud')->nullable();
            $table->string('detalle');
            $table->boolean('es_proyecto')->default(false);
            $table->string('codigo_proyecto')->nullable();

            // Foreign keys
            $table->unsignedBigInteger('cliente_id'); // cliente principal
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade')->onUpdate('cascade');

            /*$table->unsignedBigInteger('cliente_id'); // cliente principal
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade')->onUpdate('cascade');*/

            $table->unsignedBigInteger('cliente_final_id');
            $table->foreign('cliente_final_id')->references('id')->on('clientes_finales')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('supervisor_id');
            $table->foreign('supervisor_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');

            /* $table->unsignedBigInteger('estado_id');
            $table->foreign('estado_id')->references('id')->on('cambios_estados_tareas')->onDelete('cascade')->onUpdate('cascade');
*/
            $table->unsignedBigInteger('coordinador_id');
            $table->foreign('coordinador_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('tareas');
    }
};
