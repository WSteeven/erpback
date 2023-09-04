<?php

use App\Models\ComprasProveedores\ContactoProveedor;
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
        Schema::create('cmp_contactos_proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombres')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('celular')->nullable();
            $table->string('ext')->nullable();
            $table->string('correo')->nullable();
            $table->enum('tipo_contacto', [ContactoProveedor::TECNICO, ContactoProveedor::FINANCIERO, ContactoProveedor::COMERCIAL])->default(null);
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->unsignedBigInteger('proveedor_id')->nullable();
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('proveedor_id')->references('id')->on('proveedores')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmp_contactos_proveedores');
    }
};
