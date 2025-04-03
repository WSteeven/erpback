<?php

use App\Models\SSO\Accidente;
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
        Schema::create('sso_accidentes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('descripcion');
            $table->string('medidas_preventivas')->nullable();
            $table->json('empleados_involucrados');
            $table->string('fecha_hora_ocurrencia');
            $table->string('coordenadas');
            $table->string('consecuencias');
            $table->string('lugar_accidente');
            $table->enum('estado', [Accidente::CREADO, Accidente::FINALIZADO]);
            $table->unsignedBigInteger('empleado_reporta_id');

            // Foreign keys
            $table->foreign('empleado_reporta_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('sso_accidentes');
    }
};
