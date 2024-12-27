<?php

use App\Models\SSO\SolicitudDescuento;
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
        Schema::create('sso_solicitudes_descuentos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('descripcion');
            $table->enum('estado', [SolicitudDescuento::CREADO, SolicitudDescuento::PRECIOS_ESTABLECIDOS, SolicitudDescuento::DESCONTADO]);
            $table->json('detalles_productos');
            $table->unsignedBigInteger('empleado_involucrado_id');
            $table->unsignedBigInteger('empleado_solicitante_id');
            $table->unsignedBigInteger('incidente_id')->nullable();
            $table->unsignedBigInteger('cliente_id');

            // Foreign keys
            $table->foreign('empleado_involucrado_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('empleado_solicitante_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('cliente_id')->references('id')->on('clientes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('incidente_id')->references('id')->on('sso_incidentes')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('sso_solicitudes_descuentos');
    }
};
