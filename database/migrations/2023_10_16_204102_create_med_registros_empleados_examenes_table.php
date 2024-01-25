<?php

use App\Models\Medico\RegistroEmpleadoExamen;
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
        Schema::create('med_registros_empleados_examenes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('numero_registro');
            $table->text('observacion');
            $table->enum('tipo_proceso_examen', [RegistroEmpleadoExamen::INGRESO, RegistroEmpleadoExamen::OCUPACIONALES, RegistroEmpleadoExamen::REINGRESO, RegistroEmpleadoExamen::SALIDA]);

            // Foreign keys
            /*$table->unsignedBigInteger('tipo_examen_id');
            $table->foreign('tipo_examen_id')->references('id')->on('med_tipos_examenes')->onDelete('cascade')->onUpdate('cascade');*/

            $table->unsignedBigInteger('empleado_id');
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('med_registros_empleados_examenes');
    }
};
