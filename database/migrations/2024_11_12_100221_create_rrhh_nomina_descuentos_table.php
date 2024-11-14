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
        Schema::create('rrhh_nomina_descuentos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_descuento');
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('tipo_descuento_id')->nullable();
            $table->unsignedBigInteger('multa_id')->nullable();
            $table->mediumText('descripcion');
            $table->decimal('valor');
            $table->integer('cantidad_cuotas');
            $table->string('mes_inicia_cobro');
            $table->boolean('pagado')->default(false);
            $table->timestamps();

            $table->foreign('empleado_id')->references('id')->on('empleados');
            $table->foreign('tipo_descuento_id')->references('id')->on('descuentos_generales')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('multa_id')->references('id')->on('multas')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rrhh_nomina_descuentos');
    }
};
