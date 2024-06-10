<?php

use App\Models\Vehiculos\AsignacionVehiculo;
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
        Schema::create('veh_transferencias_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehiculo_id')->nullable();
            $table->unsignedBigInteger('entrega_id')->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->unsignedBigInteger('canton_id')->nullable();
            $table->string('motivo');
            $table->text('observacion_entrega')->nullable();
            $table->text('observacion_recibe')->nullable();
            $table->date('fecha_entrega');
            $table->enum('estado', [AsignacionVehiculo::PENDIENTE, AsignacionVehiculo::ACEPTADO, AsignacionVehiculo::RECHAZADO, AsignacionVehiculo::ANULADO])->default(AsignacionVehiculo::PENDIENTE);
            $table->boolean('transferido')->default(false);
            $table->boolean('devuelto')->default(false);
            $table->timestamp('fecha_devolucion')->nullable();
            $table->unsignedBigInteger('devuelve_id')->nullable();
            $table->unsignedBigInteger('asignacion_id')->nullable();
            $table->unsignedBigInteger('transferencia_id')->nullable();
            $table->text('observaciones_devolucion')->nullable();
            $table->text('accesorios')->nullable();
            $table->text('estado_carroceria')->nullable();
            $table->text('estado_mecanico')->nullable();
            $table->text('estado_electrico')->nullable();
            $table->timestamps();

            $table->foreign('vehiculo_id')->references('id')->on('vehiculos')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('entrega_id')->references('id')->on('empleados')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('responsable_id')->references('id')->on('empleados')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('canton_id')->references('id')->on('cantones')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('devuelve_id')->references('id')->on('empleados')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('asignacion_id')->references('id')->on('veh_asignaciones_vehiculos')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('transferencia_id')->references('id')->on('veh_transferencias_vehiculos')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('veh_transferencias_vehiculos');
    }
};
