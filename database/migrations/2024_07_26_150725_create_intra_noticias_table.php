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
        Schema::create('intra_noticias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->longText('descripcion');
            $table->unsignedBigInteger('autor_id');
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->string('etiquetas')->nullable();
            $table->string('imagen_noticia')->nullable();
            $table->date('fecha_vencimiento');
            $table->timestamps();

            $table->foreign('autor_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('categoria_id')->references('id')->on('intra_categorias_noticias')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intra_noticias');
    }
};
