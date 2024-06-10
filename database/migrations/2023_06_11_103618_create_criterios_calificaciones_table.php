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
        Schema::create('criterios_calificaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion');
            $table->double('ponderacion_referencia',8,2);
            $table->unsignedBigInteger('departamento_id')->nullable();
            $table->unsignedBigInteger('oferta_id')->nullable();
            $table->timestamps();

            $table->foreign('departamento_id')->references('id')->on('departamentos')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('oferta_id')->references('id')->on('ofertas_proveedores')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('criterios_calificaciones');
    }
};
