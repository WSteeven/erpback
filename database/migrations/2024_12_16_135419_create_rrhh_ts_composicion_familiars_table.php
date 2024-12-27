<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rrhh_ts_composiciones_familiares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->string('nombres_apellidos');
            $table->string('parentesco');
            $table->integer('edad');
            $table->string('estado_civil');
            $table->string('instruccion');
            $table->string('ocupacion');
            $table->string('discapacidad');
            $table->decimal('ingreso_mensual')->default(0);
            $table->unsignedBigInteger('model_id');
            $table->text('model_type');
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
        Schema::dropIfExists('rrhh_ts_composiciones_familiares');
    }
};
