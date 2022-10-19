<?php

use App\Models\ActivoFijo;
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
        Schema::create('activos_fijos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_desde');
            $table->date('fecha_hasta')->nullable();
            $table->enum('accion', [ActivoFijo::ASIGNACION, ActivoFijo::DEVOLUCION])->required();
            $table->string('observacion')->nullable();
            $table->string('lugar');
            $table->unsignedBigInteger('detalle_id');
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('sucursal_id');
            $table->unsignedBigInteger('condicion_id');
            $table->timestamps();

            $table->foreign('detalle_id')->references('id')->on('detalles_productos')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sucursal_id')->references('id')->on('sucursales')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('condicion_id')->references('id')->on('condiciones_de_productos')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activos_fijos');
    }
};
