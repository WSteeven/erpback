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
        Schema::create('ventas_vendedores', function (Blueprint $table) {
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('modalidad_id');
            $table->string('tipo_vendedor')->nullable();
            $table->unsignedBigInteger('jefe_inmediato_id')->nullable();
            $table->boolean('activo')->default(true);
            $table->text('causa_desactivacion')->nullable();
            $table->timestamps();

            $table->primary('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados')->cascadeOnUpdate();
            $table->foreign('jefe_inmediato_id')->references('id')->on('empleados')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('modalidad_id')->references('id')->on('ventas_modalidades')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas_vendedores');
    }
};
