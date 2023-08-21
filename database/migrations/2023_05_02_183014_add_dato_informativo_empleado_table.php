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
        Schema::table('empleados', function (Blueprint $table) {
            $table->string('tipo_sangre')->after('responsable_discapacitados');
            $table->text('direccion')->after('tipo_sangre');
            $table->string('correo_personal')->after('tipo_sangre');
            $table->decimal('supa',8,2)->after('direccion');
            $table->decimal('salario',8,2)->after('supa');
            $table->string('num_cuenta_bancaria')->after('salario');
            $table->boolean('tiene_discapacidad')->after('num_cuenta_bancaria');
            $table->string('fecha_ingreso')->after('tiene_discapacidad')->nullable();
            $table->string('fecha_salida')->after('fecha_ingreso')->nullable();
            $table->string('talla_zapato')->after('fecha_salida');
            $table->string('talla_camisa')->after('talla_zapato');
            $table->string('talla_guantes')->after('talla_camisa');
            $table->text('observacion')->after('fecha_salida');
            $table->unsignedBigInteger('estado_civil_id')->after('observacion')->nullable();
            $table->foreign('estado_civil_id')->references('id')->on('estado_civil');
            $table->unsignedBigInteger('area_id')->after('estado_civil_id')->nullable();
            $table->foreign('area_id')->references('id')->on('areas');
            $table->unsignedBigInteger('tipo_contrato_id')->after('area_id')->nullable();
            $table->foreign('tipo_contrato_id')->references('id')->on('tipo_contrato');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
