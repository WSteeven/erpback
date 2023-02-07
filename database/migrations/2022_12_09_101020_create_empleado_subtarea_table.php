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
        Schema::create('empleado_subtarea', function (Blueprint $table) {
            $table->id();

            $table->boolean('responsable');

            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedBigInteger('subtarea_id');
            $table->foreign('subtarea_id')->references('id')->on('subtareas')->onUpdate('cascade')->onDelete('cascade');

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
        Schema::dropIfExists('empleado_subtarea');
    }
};
