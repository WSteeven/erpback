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
        Schema::create('intra_eventos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->unsignedBigInteger('tipo_evento_id');
            $table->unsignedBigInteger('anfitrion_id');
            $table->longText('descripcion');
            $table->timestamp('fecha_hora_inicio');
            $table->timestamp('fecha_hora_fin');
            $table->boolean('es_editable')->default(true);
            $table->boolean('es_personalizado')->default(true);
            $table->timestamps();

            $table->foreign('tipo_evento_id')->references('id')->on('intra_tipos_eventos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('anfitrion_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intra_eventos');
    }
};
