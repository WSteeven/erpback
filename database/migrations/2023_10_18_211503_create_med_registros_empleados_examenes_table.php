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
            $table->string('tipo_proceso_examen');
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
