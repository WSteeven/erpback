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
        Schema::create('med_profesionales_salud', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');

            // foreign keys
            $table->unsignedBigInteger('empleado_id');
            // $table->primary('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados')->cascadeOnUpdate();

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
        Schema::dropIfExists('med_profesionales_salud');
    }
};
