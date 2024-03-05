<?php

use App\Models\Medico\SolicitudExamen;
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
        $enumEstados = [SolicitudExamen::PENDIENTE, SolicitudExamen::SOLICITADO, SolicitudExamen::APROBADO_POR_COMPRAS, SolicitudExamen::RESULTADOS, SolicitudExamen::DIAGNOSTICO_REALIZADO];

        Schema::create('med_solicitudes_examenes', function (Blueprint $table) use ($enumEstados) {
            $table->id();

            $table->string('observacion')->nullable();
            $table->string('observacion_autorizador')->nullable();
            $table->enum('estado_solicitud_examen', $enumEstados);

            // Foreign keys
            $table->unsignedBigInteger('registro_empleado_examen_id');
            $table->foreign('registro_empleado_examen_id')->references('id')->on('med_registros_empleados_examenes')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('canton_id');
            $table->foreign('canton_id')->references('id')->on('cantones')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('solicitante_id');
            $table->foreign('solicitante_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();

            $table->unsignedBigInteger('autorizador_id');
            $table->foreign('autorizador_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_solicitudes_examenes');
    }
};
