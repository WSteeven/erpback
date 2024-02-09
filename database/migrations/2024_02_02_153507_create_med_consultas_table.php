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
        Schema::create('med_consultas', function (Blueprint $table) {
            $table->string('rp');
            $table->string('prescripcion');

            //Laves foraneas
            $table->unsignedBigInteger('cita_id');
            $table->primary('cita_id');
            $table->foreign('cita_id')->references('id')->on('med_citas_medicas')->cascadeOnUpdate();

            $table->unsignedBigInteger('empleado_id');
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
        Schema::dropIfExists('med_consultas');
    }
};
