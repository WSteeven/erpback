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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_barras')->nullable();
            $table->unsignedBigInteger('nombre_id');
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('modelo_id');
            $table->double('precio')->nullable()->default('0');
            $table->string('serial')->nullable();
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('tipo_fibra_id')->nullable();
            $table->unsignedBigInteger('hilo_id')->nullable();
            $table->integer('punta_a')->nullable();
            $table->integer('punta_b')->nullable();
            $table->integer('punta_corte')->nullable();
            $table->unsignedBigInteger('condicion_id')->required()->default(1);
            $table->timestamps();

            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('modelo_id')->references('id')->on('modelos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('nombre_id')->references('id')->on('nombres_de_productos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tipo_fibra_id')->references('id')->on('tipo_fibras')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('hilo_id')->references('id')->on('hilos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('condicion_id')->references('id')->on('condiciones_de_productos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('productos');
    }
};
