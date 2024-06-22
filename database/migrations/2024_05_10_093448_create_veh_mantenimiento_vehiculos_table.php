<?php

use App\Models\Vehiculos\MantenimientoVehiculo;
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
        Schema::create('veh_mantenimientos_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehiculo_id');
            $table->unsignedBigInteger('servicio_id');
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('supervisor_id');
            $table->timestamp('fecha_realizado')->nullable();
            $table->string('km_realizado')->nullable();
            $table->string('imagen_evidencia')->nullable();
            $table->string('estado')->default(MantenimientoVehiculo::PENDIENTE);
            $table->string('km_retraso')->nullable();
            $table->integer('dias_postergado')->default(0);
            $table->text('motivo_postergacion')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();

            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('servicio_id')->references('id')->on('veh_servicios')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('empleado_id')->references('id')->on('empleados')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('supervisor_id')->references('id')->on('empleados')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veh_mantenimientos_vehiculos');
    }
};
