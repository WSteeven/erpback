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
        Schema::create('cmp_datos_bancarios_proveedores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banco_id')->nullable();
            $table->unsignedBigInteger('empresa_id')->nullable(); //razon social propietaria del numero de cta como proveedor, independientemente si es cliente o proveedores
            $table->string('tipo_cuenta');
            $table->string('numero_cuenta');
            $table->string('identificacion')->nullable();
            $table->string('nombre_propietario')->nullable();
            $table->timestamps();

            $table->foreign('banco_id')->references('id')->on('bancos')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('empresa_id')->references('id')->on('empresas')->cascadeOnUpdate()->nullOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmp_datos_bancarios_proveedores');
    }
};
