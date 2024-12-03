<?php

use App\Models\RecursosHumanos\Capacitacion\Formulario;
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
        Schema::create('rrhh_cap_formularios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->string('nombre');
            $table->json('formulario');
            $table->boolean('es_recurrente');
            $table->unsignedInteger('periodo_recurrencia')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->enum('tipo', [Formulario::INTERNO, Formulario::EXTERNO]);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->foreign('empleado_id')->references('id')->on('empleados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_cap_formularios');
    }
};
