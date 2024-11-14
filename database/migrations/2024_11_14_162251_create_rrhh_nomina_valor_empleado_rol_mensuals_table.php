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
        Schema::create('rrhh_nomina_valor_empleado_rol_mensuals', function (Blueprint $table) {
            $table->id();
            $table->string('tipo');
            $table->string('mes');
            $table->unsignedBigInteger('empleado_id');
            $table->decimal('monto');
            $table->text('model_type');
            $table->unsignedBigInteger('model_id');
            $table->unsignedBigInteger('rol_pago_id')->nullable();

            $table->timestamps();

            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('empleado_id')->references('id')->on('rol_pago')->cascadeOnUpdate()->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_nomina_valor_empleado_rol_mensuals');
    }
};
