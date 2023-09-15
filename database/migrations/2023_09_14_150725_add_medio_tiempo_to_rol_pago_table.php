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
        Schema::table('rol_pago', function (Blueprint $table) {
            $table->boolean('medio_tiempo')->default('0')->after('estado')->nullable();
            $table->decimal('salario',8,4)->change();
            $table->decimal('sueldo',8,4)->change();
            $table->decimal('decimo_tercero',8,4)->change();
            $table->decimal('decimo_cuarto',8,4)->change();
            $table->decimal('fondos_reserva',8,4)->change();
            $table->decimal('bonificacion',8,4)->change();
            $table->decimal('total_ingreso',8,4)->change();
            $table->decimal('comisiones',8,4)->change();
            $table->decimal('iess',8,4)->change();
            $table->decimal('anticipo',8,4)->change();
            $table->decimal('prestamo_quirorafario',8,4)->change();
            $table->decimal('prestamo_hipotecario',8,4)->change();
            $table->decimal('extension_conyugal',8,4)->change();
            $table->decimal('prestamo_empresarial',8,4)->change();
            $table->decimal('bono_recurente',8,4)->change();
            $table->decimal('total_egreso',8,4)->change();
            $table->decimal('total',8.4)->change();;

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rol_pago', function (Blueprint $table) {
            //
        });
    }
};
