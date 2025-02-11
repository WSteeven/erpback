<?php

use App\Models\ComprasProveedores\CuentaBancaria;
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
        Schema::create('cmp_cuentas_bancarias', function (Blueprint $table) {
            $table->id();

            $table->enum('tipo_cuenta', [CuentaBancaria::AHORRO, CuentaBancaria::CORRENTE]);
            $table->string('numero_cuenta');

            $table->foreignId('banco_id')->constrained('bancos')->onDelete('cascade');
            $table->foreignId('beneficiario_id')->constrained('cmp_beneficiarios')->onDelete('cascade');

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
        Schema::dropIfExists('cmp_cuentas_bancarias');
    }
};
