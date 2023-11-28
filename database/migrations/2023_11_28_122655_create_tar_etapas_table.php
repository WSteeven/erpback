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
        Schema::create('tar_etapas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->unsignedBigInteger('proyecto_id')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('responsable_id')->references('id')->on('empleados')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('proyecto_id')->references('id')->on('proyectos')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tar_etapas');
    }
};
