<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh_nomina_detalles_vacaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vacacion_id');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('dias_utilizados')->default(1);
            $table->unsignedBigInteger('vacacionable_id')->nullable();
            $table->string('vacacionable_type')->nullable();
            $table->mediumText('observacion')->nullable();

            $table->timestamps();

            $table->foreign('vacacion_id')->references('id')->on('rrhh_nomina_vacaciones')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_nomina_detalles_vacaciones');
    }
};
