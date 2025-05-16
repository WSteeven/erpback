<?php

use App\Models\ComprasProveedores\Pago;
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
        Schema::create('cmp_pagos', function (Blueprint $table) {
            $table->id();

            $table->enum('tipo', [Pago::PAGOS, Pago::COBROS])->default(Pago::PAGOS); // Tipo de transacción
            $table->string('num_cuenta_empresa', 11);
            // $table->integer('num_secuencial');
            $table->string('num_comprobante', 30)->nullable();
            $table->string('moneda');
            $table->string('valor', 13);
            $table->enum('forma_pago', [Pago::CREDITO_A_CUENTA, Pago::EMISION_CHEQUE, Pago::ENTREGA_EN_EFECTIVO]);
            $table->string('referencia');
            $table->string('referencia_adicional')->nullable();

            $table->foreignId('cuenta_banco_id')->constrained('cmp_cuentas_bancarias')->onDelete('cascade');
            $table->foreignId('beneficiario_id')->constrained('cmp_beneficiarios')->onDelete('cascade');
            $table->foreignId('generador_cash_id')->constrained('cmp_generador_cash')->onDelete('cascade');

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
        Schema::dropIfExists('cmp_pagos');
    }
};
