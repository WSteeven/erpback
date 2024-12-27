<?php

use App\Models\SSO\Incidente;
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
        Schema::create('sso_incidentes', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('descripcion');
            $table->string('coordenadas');
            $table->enum('tipo_incidente', [Incidente::ES_REPORTE_INCIDENTE, Incidente::ES_CAMBIO_EPP]);
            $table->enum('estado', [Incidente::CREADO, Incidente::EN_CURSO, Incidente::FINALIZADO]);
            $table->json('detalles_productos')->nullable();
            $table->unsignedBigInteger('empleado_reporta_id');
            $table->unsignedBigInteger('empleado_involucrado_id');
            $table->unsignedBigInteger('inspeccion_id')->nullable();
            $table->unsignedBigInteger('cliente_id')->nullable();

            // Foreign keys
            $table->foreign('empleado_reporta_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('empleado_involucrado_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('inspeccion_id')->references('id')->on('sso_inspecciones')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('cliente_id')->references('id')->on('clientes')->nullOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('sso_incidentes');
    }
};
