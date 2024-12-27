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
        Schema::create('sso_seguimiento_incidentes', function (Blueprint $table) {
            $table->id();
            $table->text('causa_raiz')->nullable();
            $table->text('acciones_correctivas')->nullable();
            $table->unsignedBigInteger('devolucion_id')->nullable();
            $table->unsignedBigInteger('pedido_id')->nullable();
            $table->unsignedBigInteger('solicitud_descuento_id')->nullable();
            $table->unsignedBigInteger('incidente_id');

            // Foreign keys
            $table->foreign('solicitud_descuento_id')->references('id')->on('sso_solicitudes_descuentos')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('incidente_id')->references('id')->on('sso_incidentes')->nullOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('sso_seguimiento_incidentes');
    }
};
