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
        Schema::create('med_esquemas_vacunas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dosis_aplicadas');
            $table->text('observacion')->nullable();

            // Foreign keys
            $table->unsignedBigInteger('paciente_id');
            $table->foreign('paciente_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('tipo_vacuna_id');
            $table->foreign('tipo_vacuna_id')->references('id')->on('med_tipos_vacunas')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('med_esquemas_vacunas');
    }
};
